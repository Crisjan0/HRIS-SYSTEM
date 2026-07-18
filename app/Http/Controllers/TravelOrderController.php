<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TravelOrder;
use App\Models\User;
use App\Notifications\TravelOrderNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
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

        $travelOrders = TravelOrder::with(['employee', 'companions'])
            ->where('employee_id', $employee->id)
            ->orWhereHas('companions', fn ($query) => $query->where('employees.id', $employee->id))
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
            'travel_type' => 'required|string|in:local,foreign',
            'travel_date_start' => 'required|date',
            'travel_date_end' => 'required|date|after_or_equal:travel_date_start',
            'places_of_travel' => 'required|string|max:500',
            'purpose' => 'required|string',
            'companions' => 'nullable|array',
            'companions.*' => 'exists:employees,id',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $this->validateTravelDates($validated['travel_date_start'], $validated['travel_date_end']);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('travel-order-attachments', 'public');
        }

        $travelOrder = $employee->travelOrders()->create([
            'travel_type' => $validated['travel_type'],
            'travel_date_start' => $validated['travel_date_start'],
            'travel_date_end' => $validated['travel_date_end'],
            'places_of_travel' => $validated['places_of_travel'],
            'purpose' => $validated['purpose'],
            'attachment_path' => $attachmentPath,
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

        $isTaggedCompanion = $employee
            ? $travelOrder->companions()->where('employees.id', $employee->id)->exists()
            : false;

        // Allow the owner, tagged companions, and admin roles to view
        if ($travelOrder->employee_id !== $employee?->id
            && ! $isTaggedCompanion
            && ! in_array(auth()->user()->role, ['admin', 'hrstaff', 'director', 'chief', 'regionaldirector', 'regional director'])) {
            abort(403);
        }

        $travelOrder->load(['employee', 'companions', 'chief', 'hrstaff', 'regionalDirector']);

        return view('travel-orders.show', compact('travelOrder'));
    }

    /**
     * Display all travel orders for admin/HR management.
     */
    public function adminIndex(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $travelType = $request->query('travel_type', '');
        $sort = $request->query('sort', 'latest');

        $allTravelOrders = $this->sortTravelOrders($this->filterTravelOrders(
            TravelOrder::with(['employee', 'companions'])->whereIn('status', ['approved', 'rejected']),
            $search,
            $travelType
        ), $sort)
            ->get();

        $pendingTravelOrders = $this->sortTravelOrders($this->filterTravelOrders(
            TravelOrder::with(['employee', 'companions'])->where('status', 'pending'),
            $search,
            $travelType
        ), $sort)
            ->get();

        $tab = $request->query('tab', 'pending');
        if (! in_array($tab, ['all', 'pending'])) {
            $tab = 'pending';
        }

        $travelTypes = [
            'local' => 'Local',
            'foreign' => 'Foreign',
        ];

        return view('travel-orders.admin-index', compact('allTravelOrders', 'pendingTravelOrders', 'tab', 'sort', 'search', 'travelType', 'travelTypes'));
    }

    private function filterTravelOrders($query, string $search, string $travelType)
    {
        return $query
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('places_of_travel', 'like', "%{$search}%")
                        ->orWhere('purpose', 'like', "%{$search}%")
                        ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                            $employeeQuery
                                ->where('firstname', 'like', "%{$search}%")
                                ->orWhere('middlename', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%")
                                ->orWhere('division', 'like', "%{$search}%");
                        });
                });
            })
            ->when(in_array($travelType, ['local', 'foreign'], true), fn ($query) => $query->where('travel_type', $travelType));
    }

    private function sortTravelOrders($query, string $sort)
    {
        return match ($sort) {
            'oldest' => $query->oldest(),
            'travel_date_asc' => $query->orderBy('travel_date_start')->orderBy('created_at'),
            'travel_date_desc' => $query->orderByDesc('travel_date_start')->orderByDesc('created_at'),
            'employee_asc' => $query
                ->orderBy(Employee::select('lastname')->whereColumn('employees.id', 'travel_orders.employee_id'))
                ->orderBy(Employee::select('firstname')->whereColumn('employees.id', 'travel_orders.employee_id')),
            default => $query->latest(),
        };
    }

    private function validateTravelDates(string $startDate, string $endDate): void
    {
        $today = now()->startOfDay();
        $dates = [
            'travel_date_start' => Carbon::parse($startDate)->startOfDay(),
            'travel_date_end' => Carbon::parse($endDate)->startOfDay(),
        ];

        foreach ($dates as $field => $date) {
            if ($date->lt($today) || $date->isWeekend()) {
                throw ValidationException::withMessages([
                    $field => 'Please select today or a future weekday. Saturdays and Sundays are disabled.',
                ]);
            }
        }
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
                } elseif (in_array($role, ['hrstaff', 'admin'])) {
                    $travelOrder->hrstaff_status = 'rejected';
                    $travelOrder->approved_by_hrstaff = $employeeId;
                    $travelOrder->hrstaff_remarks = $validated['remarks'];
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

                    // Notify HR/Admin (Level 2)
                    $hrUsers = User::whereHas('employee', function ($query) {
                        $query->whereIn('account_role', ['hrstaff', 'admin']);
                    })->get();

                    Notification::send($hrUsers, new TravelOrderNotification(
                        $travelOrder,
                        'Travel Order Pending HR Approval',
                        "A travel order to {$travelOrder->places_of_travel} by {$travelOrder->employee->firstname} {$travelOrder->employee->lastname} has been approved by the Chief and awaits HR approval."
                    ));
                } elseif (in_array($role, ['hrstaff', 'admin'])) {
                    $travelOrder->hrstaff_status = 'approved';
                    $travelOrder->approved_by_hrstaff = $employeeId;
                    $travelOrder->hrstaff_remarks = $validated['remarks'];

                    // Notify Regional Director (Level 3)
                    $directors = User::whereHas('employee', function ($query) {
                        $query->whereIn('account_role', ['regionaldirector', 'regional director', 'director']);
                    })->get();

                    Notification::send($directors, new TravelOrderNotification(
                        $travelOrder,
                        'Travel Order Pending Approval',
                        "A travel order to {$travelOrder->places_of_travel} by {$travelOrder->employee->firstname} {$travelOrder->employee->lastname} has been approved by HR and awaits your final approval."
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

        return redirect()->route('hr.travel-orders.index', ['tab' => 'pending'])->with('success', $msg);
    }
}
