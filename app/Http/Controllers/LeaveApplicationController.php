<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
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
    public function index(Request $request): View
    {
        $role = auth()->user()->role;
        $search = trim((string) $request->query('search', ''));
        $leaveTypeId = $request->query('leave_type_id', '');
        $sort = $request->query('sort', 'date_filed_desc');
        $query = $this->pendingLeaveApplicationsQuery($role);

        $this->applyFilters($query, $search, $leaveTypeId);
        $this->applySort($query, $sort);

        $leaves = $query->get();
        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();

        return view('leaves.applications.index', compact('leaves', 'leaveTypes', 'search', 'leaveTypeId', 'sort'));
    }

    public function filter(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $leaveTypeId = $request->query('leave_type_id', '');
        $sort = $request->query('sort', 'date_filed_desc');
        $query = $this->pendingLeaveApplicationsQuery(auth()->user()->role);

        $this->applyFilters($query, $search, $leaveTypeId);
        $this->applySort($query, $sort);

        $leaves = $query->get();

        return response()->json([
            'html' => view('leaves.applications._rows', [
                'leaves' => $leaves,
                'actionMode' => 'review',
                'emptyMessage' => __('No pending leave applications found.'),
            ])->render(),
            'count' => $leaves->count(),
        ]);
    }

    /**
     * Display a listing of all leave requests (History).
     */
    public function all(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $leaveTypeId = $request->query('leave_type_id', '');
        $status = $request->query('status', '');
        $sort = $request->query('sort', 'date_filed_desc');

        $query = LeaveRequest::with(['employee', 'leaveType'])
            ->when(in_array($status, ['pending', 'approved', 'rejected', 'cancelled'], true), fn ($query) => $query->where('status', $status));

        $this->applyFilters($query, $search, $leaveTypeId);
        $this->applySort($query, $sort);

        $leaves = $query->get();
        $leaveTypes = LeaveType::where('is_active', true)->orderBy('name')->get();

        return view('leaves.applications.all', compact('leaves', 'leaveTypes', 'search', 'leaveTypeId', 'status', 'sort'));
    }

    public function allFilter(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $leaveTypeId = $request->query('leave_type_id', '');
        $status = $request->query('status', '');
        $sort = $request->query('sort', 'date_filed_desc');

        $query = LeaveRequest::with(['employee', 'leaveType'])
            ->when(in_array($status, ['pending', 'approved', 'rejected', 'cancelled'], true), fn ($query) => $query->where('status', $status));

        $this->applyFilters($query, $search, $leaveTypeId);
        $this->applySort($query, $sort);

        $leaves = $query->get();

        return response()->json([
            'html' => view('leaves.applications._rows', [
                'leaves' => $leaves,
                'actionMode' => 'view',
                'emptyMessage' => __('No leave records found.'),
            ])->render(),
            'count' => $leaves->count(),
        ]);
    }

    private function pendingLeaveApplicationsQuery(string $role)
    {
        $query = LeaveRequest::where('status', 'pending')
            ->with(['employee', 'leaveType']);

        if ($role === 'chief') {
            $query->where('chief_status', 'pending');
        } elseif (in_array($role, ['hrstaff', 'admin'])) {
            $query->where('chief_status', 'approved')->where('hrstaff_status', 'pending');
        } elseif (in_array($role, ['regional director', 'regionaldirector', 'director'])) {
            $query->where('hrstaff_status', 'approved')->where('rd_status', 'pending');
        } else {
            $query->where('id', '<', 0);
        }

        return $query;
    }

    private function applyFilters($query, string $search, mixed $leaveTypeId): void
    {
        $query
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->whereHas('employee', function ($employeeQuery) use ($search) {
                            $employeeQuery
                                ->where('firstname', 'like', "%{$search}%")
                                ->orWhere('middlename', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%");
                        })
                        ->orWhereHas('leaveType', function ($typeQuery) use ($search) {
                            $typeQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($leaveTypeId !== '', fn ($query) => $query->where('leave_type_id', $leaveTypeId));
    }

    private function applySort($query, string $sort): void
    {
        match ($sort) {
            'date_filed_asc' => $query->orderBy('date_filed')->orderBy('created_at'),
            'leave_start_asc' => $query->orderBy('start_date')->orderBy('date_filed', 'desc'),
            'leave_start_desc' => $query->orderByDesc('start_date')->orderByDesc('date_filed'),
            'employee_asc' => $query
                ->orderBy(\App\Models\Employee::select('lastname')->whereColumn('employees.id', 'leave_requests.employee_id'))
                ->orderBy(\App\Models\Employee::select('firstname')->whereColumn('employees.id', 'leave_requests.employee_id')),
            default => $query->orderByDesc('date_filed')->orderByDesc('created_at'),
        };
    }

    /**
     * Display the specified resource for review.
     */
    public function show(LeaveRequest $leaveApplication): View
    {
        $leaveApplication->load(['employee', 'leaveType', 'chief', 'hrstaff', 'regionalDirector']);
        $leaveApplication->employee?->ensureLeaveCredits(Carbon::parse($leaveApplication->start_date)->year);
        $leaveCredit = $leaveApplication->employee?->leaveCredits()
            ->where('leave_type_id', $leaveApplication->leave_type_id)
            ->where('year', Carbon::parse($leaveApplication->start_date)->year)
            ->first();

        return view('leaves.applications.show', compact('leaveApplication', 'leaveCredit'));
    }

    public function print(LeaveRequest $leaveApplication): View
    {
        $leaveApplication->load(['employee.user', 'employee.leaveCredits.leaveType', 'employee.pdsGovId', 'leaveType', 'chief', 'hrstaff', 'regionalDirector']);
        $leaveApplication->employee?->ensureLeaveCredits(Carbon::parse($leaveApplication->start_date)->year);

        return view('leaves.print', ['leaveRequest' => $leaveApplication]);
    }

    public function update(Request $request, LeaveRequest $leaveApplication): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        $role = auth()->user()->role;
        $employeeId = auth()->user()->employee?->id;
        $remarks = $validated['remarks'] ?? null;

        DB::transaction(function () use ($validated, $leaveApplication, $role, $employeeId, $remarks) {

            if ($validated['status'] === 'rejected') {
                $leaveApplication->status = 'rejected';

                if ($role === 'chief') {
                    $leaveApplication->chief_status = 'rejected';
                    $leaveApplication->approved_by_chief = $employeeId;
                    $leaveApplication->chief_remarks = $remarks;
                } elseif (in_array($role, ['hrstaff', 'admin'])) {
                    $leaveApplication->hrstaff_status = 'rejected';
                    $leaveApplication->approved_by_hrstaff = $employeeId;
                    $leaveApplication->hrstaff_remarks = $remarks;
                } elseif (in_array($role, ['regional director', 'regionaldirector', 'director'])) {
                    $leaveApplication->rd_status = 'rejected';
                    $leaveApplication->approved_by_regionaldirector = $employeeId;
                    $leaveApplication->rd_remarks = $remarks;
                }
            } else { // approved
                if ($role === 'chief') {
                    $leaveApplication->chief_status = 'approved';
                    $leaveApplication->approved_by_chief = $employeeId;
                    $leaveApplication->chief_remarks = $remarks;
                } elseif (in_array($role, ['hrstaff', 'admin'])) {
                    $leaveApplication->hrstaff_status = 'approved';
                    $leaveApplication->approved_by_hrstaff = $employeeId;
                    $leaveApplication->hrstaff_remarks = $remarks;
                } elseif (in_array($role, ['regional director', 'regionaldirector', 'director'])) {
                    $leaveApplication->rd_status = 'approved';
                    $leaveApplication->approved_by_regionaldirector = $employeeId;
                    $leaveApplication->rd_remarks = $remarks;

                    // Final stage approval
                    $leaveApplication->status = 'approved';

                    $duration = $leaveApplication->duration;
                    $credit = $leaveApplication->employee->leaveCredits()
                        ->where('leave_type_id', $leaveApplication->leave_type_id)
                        ->where('year', Carbon::parse($leaveApplication->start_date)->year)
                        ->first();

                    $requestedWithPay = $leaveApplication->is_paid !== false;

                    if ($requestedWithPay && $credit && $credit->balance >= $duration) {
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
                        $query->whereIn('account_role', ['hrstaff', 'admin']);
                    })->get();
                    Notification::send($nextLevelUsers, new LeaveRequestNotification($leaveApplication));
                } elseif (in_array($role, ['hrstaff', 'admin'])) {
                    // HR/Admin approved -> Notify Level 3 (Regional Director)
                    $nextLevelUsers = User::whereHas('employee', function ($query) {
                        $query->whereIn('account_role', ['regional director', 'regionaldirector', 'director']);
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
