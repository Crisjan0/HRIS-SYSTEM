<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of pending leave requests.
     */
    public function index(): View
    {
        $leaves = LeaveRequest::where('status', 'pending')
            ->with(['employee', 'leaveType'])
            ->latest('date_filed')
            ->get();

        return view('leave-applications.index', compact('leaves'));
    }

    /**
     * Display a listing of all leave requests (History).
     */
    public function all(): View
    {
        $leaves = LeaveRequest::with(['employee', 'leaveType'])
            ->latest('date_filed')
            ->get();

        return view('leave-applications.all', compact('leaves'));
    }

    /**
     * Update the status of a leave request (Approve/Reject).
     */
    public function update(Request $request, LeaveRequest $leaveApplication): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function() use ($validated, $leaveApplication) {
            // Only deduct credits if changing from pending to approved
            if ($validated['status'] === 'approved' && $leaveApplication->status === 'pending') {
                $duration = \Carbon\Carbon::parse($leaveApplication->start_date)->diffInDays(\Carbon\Carbon::parse($leaveApplication->end_date)) + 1;
                
                $credit = $leaveApplication->employee->leaveCredits()
                    ->where('leave_type_id', $leaveApplication->leave_type_id)
                    ->where('year', \Carbon\Carbon::parse($leaveApplication->start_date)->year)
                    ->first();

                if ($credit) {
                    $credit->decrement('balance', $duration);
                }
            }

            $leaveApplication->update($validated);
        });

        $msg = $validated['status'] === 'approved' ? 'Leave request approved and credits deducted.' : 'Leave request rejected.';
        
        return redirect()->route('leave-applications.index')->with('success', $msg);
    }
}
