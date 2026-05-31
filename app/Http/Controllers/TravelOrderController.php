<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TravelOrder;
use App\Models\User;
use App\Notifications\TravelOrderNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class TravelOrderController extends Controller
{
    /**
     * Display a listing of the employee's travel orders.
     */
    public function index(): View
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            abort(404, 'Employee record not found.');
        }

        $travelOrders = $employee->travelOrders()
            ->with('companions')
            ->latest()
            ->get();

        return view('travel-orders.index', compact('travelOrders'));
    }

    /**
     * Show the form for creating a new travel order.
     */
    public function create(): View
    {
        $employees = Employee::where('id', '!=', auth()->user()->employee?->id)
            ->orderBy('lastname')
            ->get();

        return view('travel-orders.create', compact('employees'));
    }

    /**
     * Store a newly created travel order.
     */
    public function store(Request $request): RedirectResponse
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'travel_type' => 'required|string|in:local,foreign,official_business',
            'travel_date_start' => 'required|date',
            'travel_date_end' => 'required|date|after_or_equal:travel_date_start',
            'places_of_travel' => 'required|string|max:500',
            'purpose' => 'required|string',
            'companions' => 'nullable|array',
            'companions.*' => 'exists:employees,id',
        ]);

        $travelOrder = $employee->travelOrders()->create([
            'travel_type' => $validated['travel_type'],
            'travel_date_start' => $validated['travel_date_start'],
            'travel_date_end' => $validated['travel_date_end'],
            'places_of_travel' => $validated['places_of_travel'],
            'purpose' => $validated['purpose'],
        ]);

        if (! empty($validated['companions'])) {
            $travelOrder->companions()->attach($validated['companions']);
        }

        // Notify Chief (Level 1)
        $chiefs = User::whereHas('employee', function ($query) {
            $query->where('account_role', 'chief');
        })->get();

        $employeeName = $employee->firstname.' '.$employee->lastname;
        Notification::send($chiefs, new TravelOrderNotification(
            $travelOrder,
            'New Travel Order',
            "{$employeeName} submitted a travel order to {$travelOrder->places_of_travel}."
        ));

        return redirect()->route('travel-orders.index')->with('success', 'Travel order created successfully.');
    }

    /**
     * Display the specified travel order.
     */
    public function show(TravelOrder $travelOrder): View
    {
        $employee = auth()->user()->employee;

        // Allow the owner and admin roles to view
        if ($travelOrder->employee_id !== $employee?->id
            && ! in_array(auth()->user()->role, ['admin', 'hrstaff', 'director', 'chief', 'regionaldirector', 'regional director'])) {
            abort(403);
        }

        $travelOrder->load(['employee', 'companions', 'chief', 'regionalDirector']);

        return view('travel-orders.show', compact('travelOrder'));
    }

    /**
     * Display all travel orders for admin/HR management.
     */
    public function adminIndex(): View
    {
        $travelOrders = TravelOrder::with(['employee', 'companions'])
            ->latest()
            ->get();

        return view('travel-orders.admin-index', compact('travelOrders'));
    }

    /**
     * Approve or reject a travel order.
     */
    public function updateStatus(Request $request, TravelOrder $travelOrder): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        $role = auth()->user()->role;
        $employeeId = auth()->user()->employee?->id;

        DB::transaction(function () use ($validated, $travelOrder, $role, $employeeId) {
            if ($validated['status'] === 'rejected') {
                $travelOrder->status = 'rejected';

                if ($role === 'chief') {
                    $travelOrder->chief_status = 'rejected';
                    $travelOrder->approved_by_chief = $employeeId;
                    $travelOrder->chief_remarks = $validated['remarks'];
                } elseif (in_array($role, ['regional director', 'regionaldirector', 'director'])) {
                    $travelOrder->rd_status = 'rejected';
                    $travelOrder->approved_by_regionaldirector = $employeeId;
                    $travelOrder->rd_remarks = $validated['remarks'];
                }

                // Notify the employee
                $travelOrder->employee->user->notify(new TravelOrderNotification(
                    $travelOrder,
                    'Travel Order Rejected',
                    "Your travel order to {$travelOrder->places_of_travel} has been rejected."
                ));
            } else {
                // Approved
                if ($role === 'chief') {
                    $travelOrder->chief_status = 'approved';
                    $travelOrder->approved_by_chief = $employeeId;
                    $travelOrder->chief_remarks = $validated['remarks'];

                    // Notify Regional Director (Level 2)
                    $directors = User::whereHas('employee', function ($query) {
                        $query->whereIn('account_role', ['regionaldirector', 'regional director', 'director']);
                    })->get();

                    Notification::send($directors, new TravelOrderNotification(
                        $travelOrder,
                        'Travel Order Pending Approval',
                        "A travel order to {$travelOrder->places_of_travel} by {$travelOrder->employee->firstname} {$travelOrder->employee->lastname} has been approved by the Chief and awaits your final approval."
                    ));
                } elseif (in_array($role, ['regional director', 'regionaldirector', 'director'])) {
                    $travelOrder->rd_status = 'approved';
                    $travelOrder->approved_by_regionaldirector = $employeeId;
                    $travelOrder->rd_remarks = $validated['remarks'];

                    // Final approval
                    $travelOrder->status = 'approved';

                    // Notify the employee
                    $travelOrder->employee->user->notify(new TravelOrderNotification(
                        $travelOrder,
                        'Travel Order Approved',
                        "Your travel order to {$travelOrder->places_of_travel} has been fully approved."
                    ));
                }
            }

            $travelOrder->save();
        });

        $msg = $validated['status'] === 'approved' ? 'Travel order approved successfully.' : 'Travel order rejected.';

        return redirect()->route('hr.travel-orders.index')->with('success', $msg);
    }
}
