<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Notifications\LeaveRequestNotification;
use App\Notifications\LeaveStatusUpdatedNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class LeaveApplicationController extends Controller
{
    public function index(): View
    {
        $role = auth()->user()->role;
        $query = LeaveRequest::where('status', 'pending')
            ->with(['employee', 'leaveType']);

        if ($role === 'chief') {
            $query->where('chief_status', 'pending');
        } elseif (in_array($role, ['hrstaff', 'admin'])) {
            $query->where('chief_status', 'approved')->where('hrstaff_status', 'pending');
        } elseif (in_array($role, ['regional director', 'regionaldirector', 'director'])) {
            $query->where('hrstaff_status', 'approved')->where('rd_status', 'pending');
        } else {
            // Fail-safe for roles that shouldn't see pending leaves here
            $query->where('id', '<', 0);
        }

        $leaves = $query->latest('date_filed')->get();

        return view('leaves.applications.index', compact('leaves'));
    }

    /**
     * Display a listing of all leave requests (History).
     */
    public function all(): View
    {
        $leaves = LeaveRequest::with(['employee', 'leaveType'])
            ->latest('date_filed')
            ->get();

        return view('leaves.applications.all', compact('leaves'));
    }

    /**
     * Display the specified resource for review.
     */
    public function show(LeaveRequest $leaveApplication): View
    {
        $leaveApplication->load(['employee', 'leaveType', 'chief', 'hrstaff', 'regionalDirector']);

        return view('leaves.applications.show', compact('leaveApplication'));
    }

    public function update(Request $request, LeaveRequest $leaveApplication): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        $role = auth()->user()->role;
        $employeeId = auth()->user()->employee?->id;

        DB::transaction(function () use ($validated, $leaveApplication, $role, $employeeId) {

            if ($validated['status'] === 'rejected') {
                $leaveApplication->status = 'rejected';

                if ($role === 'chief') {
                    $leaveApplication->chief_status = 'rejected';
                    $leaveApplication->approved_by_chief = $employeeId;
                    $leaveApplication->chief_remarks = $validated['remarks'];
                } elseif (in_array($role, ['hrstaff', 'admin'])) {
                    $leaveApplication->hrstaff_status = 'rejected';
                    $leaveApplication->approved_by_hrstaff = $employeeId;
                    $leaveApplication->hrstaff_remarks = $validated['remarks'];
                } elseif (in_array($role, ['regional director', 'regionaldirector', 'director'])) {
                    $leaveApplication->rd_status = 'rejected';
                    $leaveApplication->approved_by_regionaldirector = $employeeId;
                    $leaveApplication->rd_remarks = $validated['remarks'];
                }
            } else { // approved
                if ($role === 'chief') {
                    $leaveApplication->chief_status = 'approved';
                    $leaveApplication->approved_by_chief = $employeeId;
                    $leaveApplication->chief_remarks = $validated['remarks'];
                } elseif (in_array($role, ['hrstaff', 'admin'])) {
                    $leaveApplication->hrstaff_status = 'approved';
                    $leaveApplication->approved_by_hrstaff = $employeeId;
                    $leaveApplication->hrstaff_remarks = $validated['remarks'];
                } elseif (in_array($role, ['regional director', 'regionaldirector', 'director'])) {
                    $leaveApplication->rd_status = 'approved';
                    $leaveApplication->approved_by_regionaldirector = $employeeId;
                    $leaveApplication->rd_remarks = $validated['remarks'];

                    // Final stage approval
                    $leaveApplication->status = 'approved';

                    $duration = $leaveApplication->duration;
                    $credit = $leaveApplication->employee->leaveCredits()
                        ->where('leave_type_id', $leaveApplication->leave_type_id)
                        ->where('year', Carbon::parse($leaveApplication->start_date)->year)
                        ->first();

                    if ($credit && $credit->balance >= $duration) {
                        $credit->decrement('balance', $duration);
                        $leaveApplication->is_paid = true;
                    } else {
                        $leaveApplication->is_paid = false;
                    }
                }

                // Notify specific next level roles on approval
                if ($role === 'chief') {
                    // Chief approved -> Notify Level 2 (HR/Admin)
                    $nextLevelUsers = User::whereHas('employee', function ($query) {
                        $query->whereIn('role', ['hrstaff', 'admin']);
                    })->get();
                    Notification::send($nextLevelUsers, new LeaveRequestNotification($leaveApplication));
                } elseif (in_array($role, ['hrstaff', 'admin'])) {
                    // HR/Admin approved -> Notify Level 3 (Regional Director)
                    $nextLevelUsers = User::whereHas('employee', function ($query) {
                        $query->whereIn('role', ['regional director', 'regionaldirector', 'director']);
                    })->get();
                    Notification::send($nextLevelUsers, new LeaveRequestNotification($leaveApplication));
                }
            }

            $leaveApplication->save();

            // Notify the employee of the status change if it's final (rejected or fully approved)
            if ($leaveApplication->status !== 'pending') {
                $leaveApplication->employee->user->notify(new LeaveStatusUpdatedNotification($leaveApplication));
            }
        });

        $msg = $validated['status'] === 'approved' ? 'Leave request approved successfully.' : 'Leave request rejected.';

        return redirect()->route('leave-applications.index')->with('success', $msg);
    }
}
