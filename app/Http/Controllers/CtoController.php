<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\CtoRequestNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\CtoRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CtoController extends Controller
{
    public function adminIndex(Request $request): View
    {
        $role = strtolower(auth()->user()->role ?? '');
        $isHR = in_array($role, ['admin', 'hrstaff', 'hr staff'], true);
        $isChief = $role === 'chief';
        $isRegionalDirector = $role === 'regionaldirector';
        $search = trim((string) $request->query('search', ''));
        $type = $request->query('type', '');
        $sort = $request->query('sort', 'latest');

        $allCtoRequests = $this->sortCtoRequests($this->filterCtoRequests(
            CtoRequest::with('employee')->whereIn('status', ['approved', 'rejected']),
            $search,
            $type
        ), $sort)
            ->get();

        $pendingCtoRequests = $this->sortCtoRequests($this->filterCtoRequests(
            CtoRequest::with('employee')
                ->where('status', 'pending')
                ->when($isHR, fn ($query) => $query->where('hrstaff_status', 'pending'))
                ->when($isChief, fn ($query) => $query
                    ->whereIn('hrstaff_status', ['approved', 'rejected'])
                    ->where('chief_status', 'pending'))
                ->when($isRegionalDirector, fn ($query) => $query
                    ->whereIn('hrstaff_status', ['approved', 'rejected'])
                    ->where('chief_status', 'approved')
                    ->where('rd_status', 'pending')),
            $search,
            $type
        ), $sort)
            ->get();

        $tab = $request->query('tab', 'pending');
        if (! in_array($tab, ['all', 'pending'])) {
            $tab = 'pending';
        }

        return view('my-cto.admin-index', compact('allCtoRequests', 'pendingCtoRequests', 'tab', 'sort', 'search', 'type'));
    }

    private function filterCtoRequests($query, string $search, string $type)
    {
        return $query
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('purpose', 'like', "%{$search}%")
                        ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                            $employeeQuery
                                ->where('firstname', 'like', "%{$search}%")
                                ->orWhere('middlename', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%")
                                ->orWhere('division', 'like', "%{$search}%");
                        });
                });
            })
            ->when(in_array($type, ['earn', 'use'], true), fn ($query) => $query->where('type', $type));
    }

    private function sortCtoRequests($query, string $sort)
    {
        return match ($sort) {
            'oldest' => $query->oldest(),
            'period_asc' => $query->orderBy('date_start')->orderBy('created_at'),
            'period_desc' => $query->orderByDesc('date_start')->orderByDesc('created_at'),
            'employee_asc' => $query
                ->orderBy(Employee::select('lastname')->whereColumn('employees.id', 'cto_requests.employee_id'))
                ->orderBy(Employee::select('firstname')->whereColumn('employees.id', 'cto_requests.employee_id')),
            default => $query->latest(),
        };
    }

    public function updateStatus(Request $request, CtoRequest $ctoRequest): RedirectResponse
    {
        $role = strtolower(auth()->user()->role ?? '');
        $employeeId = auth()->user()->employee?->id;
        $isHR = in_array($role, ['admin', 'hrstaff', 'hr staff'], true);

        $rules = [
            'status' => 'required|in:approved,rejected',
            'remarks' => ($isHR && $request->input('status') === 'rejected') ? 'required|string' : 'nullable|string',
        ];

        $validated = $request->validate($rules, [
            'remarks.required' => 'Remarks are required when rejecting a request.',
        ]);

        $remarks = $validated['remarks'] ?? null;

        DB::transaction(function () use ($validated, $ctoRequest, $role, $employeeId, $isHR, $remarks) {
            if ($validated['status'] === 'rejected') {
                if ($isHR) {
                    $ctoRequest->hrstaff_status = 'rejected';
                    $ctoRequest->approved_by_hrstaff = $employeeId;
                    $ctoRequest->hrstaff_remarks = $remarks;
                    
                    $chiefs = User::whereHas('employee', function ($query) {
                        $query->where('account_role', 'chief');
                    })->get();

                    Notification::send($chiefs, new CtoRequestNotification(
                        $ctoRequest,
                        'CTO Request Disapproved by HR (Proceeding to Chief)',
                        "A CTO request by {$ctoRequest->employee->firstname} {$ctoRequest->employee->lastname} has been rejected by HR but awaits your final decision."
                    ));
                } else {
                    $ctoRequest->status = 'rejected';
                    if ($role === 'chief') {
                        $ctoRequest->chief_status = 'rejected';
                        $ctoRequest->approved_by_chief = $employeeId;
                        $ctoRequest->chief_remarks = $remarks;
                    } elseif ($role === 'regionaldirector') {
                        $ctoRequest->rd_status = 'rejected';
                        $ctoRequest->approved_by_regionaldirector = $employeeId;
                        $ctoRequest->rd_remarks = $remarks;
                    }
                }
            } else {
                if ($isHR) {
                    $ctoRequest->hrstaff_status = 'approved';
                    $ctoRequest->approved_by_hrstaff = $employeeId;
                    $ctoRequest->hrstaff_remarks = $remarks;

                    $chiefs = User::whereHas('employee', function ($query) {
                        $query->where('account_role', 'chief');
                    })->get();

                    Notification::send($chiefs, new CtoRequestNotification(
                        $ctoRequest,
                        'CTO Request Verified',
                        "A CTO request by {$ctoRequest->employee->firstname} {$ctoRequest->employee->lastname} has been verified by HR and awaits your approval."
                    ));
                } elseif ($role === 'chief' && in_array($ctoRequest->hrstaff_status, ['approved', 'rejected'], true)) {
                    $ctoRequest->chief_status = 'approved';
                    $ctoRequest->approved_by_chief = $employeeId;
                    $ctoRequest->chief_remarks = $remarks;

                    $regionalDirectors = User::whereHas('employee', function ($query) {
                        $query->where('account_role', 'regionaldirector');
                    })->get();

                    Notification::send($regionalDirectors, new CtoRequestNotification(
                        $ctoRequest,
                        'CTO Request Pending Approval',
                        "A CTO request by {$ctoRequest->employee->firstname} {$ctoRequest->employee->lastname} has been approved by the Chief and awaits your approval."
                    ));
                } elseif ($role === 'regionaldirector'
                    && in_array($ctoRequest->hrstaff_status, ['approved', 'rejected'], true)
                    && $ctoRequest->chief_status === 'approved') {
                    $ctoRequest->rd_status = 'approved';
                    $ctoRequest->approved_by_regionaldirector = $employeeId;
                    $ctoRequest->rd_remarks = $remarks;
                    $ctoRequest->status = 'approved';

                    $employee = $ctoRequest->employee;
                    if ($ctoRequest->type === 'earn') {
                        $employee->increment('cto_balance', $ctoRequest->hours);
                    } else {
                        $employee->decrement('cto_balance', $ctoRequest->hours);
                    }
                }
            }

            $ctoRequest->save();
        });

        $msg = $validated['status'] === 'approved' ? 'CTO request verified successfully.' : 'CTO request updated.';

        return redirect()->route('hr.cto.index', ['tab' => 'pending'])->with('success', $msg);
    }
}
