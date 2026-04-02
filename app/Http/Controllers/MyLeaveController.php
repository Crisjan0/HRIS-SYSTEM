<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use App\Notifications\LeaveRequestNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class MyLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            abort(404, 'Employee record not found.');
        }

        $employee->ensureLeaveCredits(now()->year);
        $leaves = $employee->leaveRequests()->with('leaveType')->latest()->get();
        $credits = $employee->leaveCredits()->with('leaveType')->get();

        return view('leaves.index', compact('leaves', 'credits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $employee = auth()->user()->employee;
        $employee?->ensureLeaveCredits(now()->year);

        $leaveTypes = LeaveType::where('is_active', true)->get();
        $credits = $employee?->leaveCredits->keyBy('leave_type_id');

        return view('leaves.create', compact('leaveTypes', 'credits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        // Calculate duration and reserved credits (pending)
        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        $duration = $start->diffInDays($end) + 1;

        // Sum of days in pending requests for this type/year
        $pendingDays = $employee->leaveRequests()
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('status', 'pending')
            ->get()
            ->sum(function ($req) {
                return Carbon::parse($req->start_date)->diffInDays(Carbon::parse($req->end_date)) + 1;
            });

        // Check credits
        $credit = $employee->leaveCredits()->where('leave_type_id', $validated['leave_type_id'])
            ->where('year', $start->year)
            ->first();

        $availableBalance = ($credit?->balance ?? 0) - $pendingDays;

        if (! $credit || $availableBalance < $duration) {
            return back()->withInput()->with('error', 'Insufficient leave credits. (Requested: '.$duration.' days, Available: '.$availableBalance.' days. You have '.$pendingDays.' days pending approval).');
        }

        // Create request (Deduction only happens on Approval)
        $leaveRequest = $employee->leaveRequests()->create([
            ...$validated,
            'date_filed' => now(),
        ]);

        // Notify only the Chief (Level 1 Approval)
        $chiefs = User::whereHas('employee', function ($query) {
            $query->where('role', 'chief');
        })->get();

        Notification::send($chiefs, new LeaveRequestNotification($leaveRequest));

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully. Credits will be deducted once approved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaf): View
    {
        $employee = auth()->user()->employee;
        if ($leaf->employee_id !== $employee?->id) {
            abort(403);
        }

        $leaf->load(['leaveType', 'chief', 'hrstaff', 'regionalDirector']);

        return view('leaves.show', compact('leaf'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveRequest $leaf): View|RedirectResponse
    {
        $employee = auth()->user()->employee;
        if ($leaf->employee_id !== $employee?->id) {
            abort(403);
        }

        if ($leaf->status !== 'pending') {
            return redirect()->route('leaves.index')->with('error', 'Only pending requests can be edited.');
        }

        $leaveTypes = LeaveType::where('is_active', true)->get();
        $credits = $employee->leaveCredits->keyBy('leave_type_id');

        return view('leaves.edit', compact('leaf', 'leaveTypes', 'credits'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveRequest $leaf): RedirectResponse
    {
        $employee = auth()->user()->employee;
        if ($leaf->employee_id !== $employee?->id || $leaf->status !== 'pending') {
            abort(403);
        }

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $leaf->update($validated);

        return redirect()->route('leaves.index')->with('success', 'Leave request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequest $leaf): RedirectResponse
    {
        $employee = auth()->user()->employee;
        if ($leaf->employee_id !== $employee?->id) {
            abort(403);
        }

        // Standard: only pending can be deleted/cancelled
        if ($leaf->status !== 'pending') {
            return redirect()->route('leaves.index')->with('error', 'Only pending requests can be cancelled.');
        }

        $leaf->delete();

        return redirect()->route('leaves.index')->with('success', 'Leave request cancelled successfully.');
    }
}
