<?php

namespace App\Http\Controllers;

use App\Models\LocatorSlip;
use App\Models\User;
use App\Notifications\LocatorSlipNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LocatorSlipController extends Controller
{
    public function index()
    {
        $locatorSlips = LocatorSlip::where('employee_id', Auth::user()->employee->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('locator-slip.index', compact('locatorSlips'));
    }

    public function create()
    {
        return view('locator-slip.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_covered' => 'required|date',
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string',
            'type' => 'required|string|in:Official Business,Personal',
            'time_from' => 'required',
            'time_to' => 'required',
        ]);
        $this->validateCoveredDate($request->date_covered);

        $locatorSlip = LocatorSlip::create([
            'employee_id' => Auth::user()->employee->id,
            'date_covered' => $request->date_covered,
            'destination' => $request->destination,
            'purpose' => $request->purpose,
            'type' => $request->type,
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
            'status' => 'pending',
        ]);

        // Notify Chief
        $chiefs = User::whereHas('employee', function ($query) {
            $query->where('account_role', 'CHIEF');
        })->get();

        foreach ($chiefs as $chief) {
            $chief->notify(new LocatorSlipNotification($locatorSlip, 'New Locator Slip', Auth::user()->employee->firstname . ' ' . Auth::user()->employee->lastname . ' submitted a new locator slip.'));
        }

        return redirect()->route('locator-slips.index')->with('message', 'Locator slip submitted successfully.');
    }

    public function manageIndex(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $type = $request->query('type', '');
        $status = $request->query('status', '');
        $sort = $request->query('sort', 'latest');

        $allLocatorSlips = $this->sortLocatorSlips($this->filterLocatorSlips(
            LocatorSlip::with('employee')->whereIn('status', ['approved', 'rejected', 'approved by chief']),
            $search,
            $type,
            $status
        ), $sort)
            ->get();

        $pendingLocatorSlips = $this->getPendingLocatorSlips($sort, $search, $type);

        $tab = $request->query('tab', 'pending');
        if (! in_array($tab, ['all', 'pending'])) {
            $tab = 'pending';
        }

        $locatorTypes = collect(['Official Business', 'Personal']);

        return view('locator-slip.hr.manage', compact('allLocatorSlips', 'pendingLocatorSlips', 'tab', 'sort', 'search', 'type', 'status', 'locatorTypes'));
    }

    public function allIndex()
    {
        return redirect()->route('hr.locator-slips.index', ['tab' => 'all']);
    }

    public function pendingIndex()
    {
        return redirect()->route('hr.locator-slips.index', ['tab' => 'pending']);
    }

    private function getPendingLocatorSlips(string $sort = 'latest', string $search = '', string $type = '')
    {
        $user = Auth::user();
        $query = LocatorSlip::with('employee');

        $query->where('status', 'pending');

        return $this->sortLocatorSlips($this->filterLocatorSlips($query, $search, $type, ''), $sort)->get();
    }

    private function filterLocatorSlips($query, string $search, string $type, string $status)
    {
        return $query
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('destination', 'like', "%{$search}%")
                        ->orWhere('purpose', 'like', "%{$search}%")
                        ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                            $employeeQuery
                                ->where('firstname', 'like', "%{$search}%")
                                ->orWhere('middlename', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%");
                        });
                });
            })
            ->when($type !== '', fn ($query) => $query->where('type', $type))
            ->when($status === 'approved', fn ($query) => $query->whereIn('status', ['approved', 'approved by chief']))
            ->when($status === 'rejected', fn ($query) => $query->where('status', 'rejected'));
    }

    private function sortLocatorSlips($query, string $sort)
    {
        return match ($sort) {
            'oldest' => $query->oldest(),
            'date_asc' => $query->orderBy('date_covered')->orderBy('created_at'),
            'date_desc' => $query->orderByDesc('date_covered')->orderByDesc('created_at'),
            'employee_asc' => $query
                ->orderBy(\App\Models\Employee::select('lastname')->whereColumn('employees.id', 'locator_slips.employee_id'))
                ->orderBy(\App\Models\Employee::select('firstname')->whereColumn('employees.id', 'locator_slips.employee_id')),
            default => $query->latest(),
        };
    }

    public function approve(LocatorSlip $locatorSlip)
    {
        $user = Auth::user();

        if (strtolower($user->role) === 'chief' && $locatorSlip->status === 'pending') {
            $locatorSlip->status = 'approved';
            $locatorSlip->approved_by_chief_id = $user->id;
            $locatorSlip->approved_by_chief_name = $user->name;
            $locatorSlip->chief_approval_date = now();
            $locatorSlip->save();

            $locatorSlip->employee->user?->notify(new LocatorSlipNotification($locatorSlip, 'Locator Slip Approved', 'Your locator slip for ' . $locatorSlip->date_covered . ' has been approved.'));

            return redirect()->back()->with('message', 'Locator slip approved.');
        }

        return redirect()->back()->with('error', 'You are not authorized to approve this locator slip or it is not in a valid state for approval.');
    }

    public function reject(LocatorSlip $locatorSlip)
    {
        if (! in_array(strtolower(Auth::user()->role), ['admin', 'hrstaff', 'chief'])) {
            abort(403);
        }

        $locatorSlip->status = 'rejected';
        $locatorSlip->save();

        // Notify Employee
        $locatorSlip->employee->user->notify(new LocatorSlipNotification($locatorSlip, 'Locator Slip Rejected', 'Your locator slip for ' . $locatorSlip->date_covered . ' has been rejected.'));

        return redirect()->back()->with('message', 'Locator slip rejected.');
    }

    public function hrShow(LocatorSlip $locatorSlip)
    {
        // View for HR/Director side
        if (!in_array(strtolower(Auth::user()->role), ['admin', 'hrstaff', 'chief'])) {
            abort(403);
        }

        return view('locator-slip.hr.show', compact('locatorSlip'));
    }

    public function show(LocatorSlip $locatorSlip)
    {
        // Ensure the user is authorized to see this slip
        if (Auth::user()->employee->id !== $locatorSlip->employee_id && !in_array(strtolower(Auth::user()->role), ['admin', 'hrstaff', 'chief'])) {
            abort(403);
        }

        return view('locator-slip.show', compact('locatorSlip'));
    }

    public function print(LocatorSlip $locatorSlip)
    {
        if (Auth::user()->employee->id !== $locatorSlip->employee_id && !in_array(strtolower(Auth::user()->role), ['admin', 'hrstaff', 'chief'])) {
            abort(403);
        }

        $locatorSlip->load('employee.user');

        return view('locator-slip.print', compact('locatorSlip'));
    }

    public function edit(LocatorSlip $locatorSlip)
    {
        // Ensure the user is authorized to edit this slip
        if (Auth::user()->employee->id !== $locatorSlip->employee_id) {
            abort(403);
        }

        return view('locator-slip.edit', compact('locatorSlip'));
    }

    public function update(Request $request, LocatorSlip $locatorSlip)
    {
        // Ensure the user is authorized to update this slip
        if (Auth::user()->employee->id !== $locatorSlip->employee_id) {
            abort(403);
        }

        $request->validate([
            'date_covered' => 'required|date',
            'type' => 'required|string|in:Official Business,Personal',
            'purpose' => 'required|string',
            'time_from' => 'required',
            'time_to' => 'required',
        ]);
        $this->validateCoveredDate($request->date_covered);

        $locatorSlip->update($request->all());

        return redirect()->route('locator-slips.show', $locatorSlip)->with('message', 'Locator slip updated successfully.');
    }

    private function validateCoveredDate(string $date): void
    {
        $coveredDate = Carbon::parse($date)->startOfDay();

        if ($coveredDate->lt(today()) || $coveredDate->isWeekend()) {
            throw ValidationException::withMessages([
                'date_covered' => 'Please select today or a future weekday. Saturdays and Sundays are disabled.',
            ]);
        }
    }
}
