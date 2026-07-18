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
        $isChief = $role === 'chief';
        $isHR = in_array($role, ['hrstaff', 'hr staff', 'admin']);
        $isRegionalDirector = in_array($role, ['director', 'regionaldirector', 'regional director']);
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
                ->when($isChief, fn ($query) => $query->where('chief_status', 'pending'))
                ->when($isHR, fn ($query) => $query
                    ->where('chief_status', 'approved')
                    ->where('hrstaff_status', 'pending'))
                ->when($isRegionalDirector, fn ($query) => $query
                    ->where('chief_status', 'approved')
                    ->where('hrstaff_status', 'approved')
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
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        $role = strtolower(auth()->user()->role ?? '');
        $employeeId = auth()->user()->employee?->id;

        DB::transaction(function () use ($validated, $ctoRequest, $role, $employeeId) {
            if ($validated['status'] === 'rejected') {
                $ctoRequest->status = 'rejected';

                if ($role === 'chief') {
                    $ctoRequest->chief_status = 'rejected';
                    $ctoRequest->approved_by_chief = $employeeId;
                    $ctoRequest->chief_remarks = $validated['remarks'];
                } elseif (in_array($role, ['hrstaff', 'hr staff', 'admin'])) {
                    $ctoRequest->hrstaff_status = 'rejected';
                    $ctoRequest->approved_by_hrstaff = $employeeId;
                    $ctoRequest->hrstaff_remarks = $validated['remarks'];
                } elseif (in_array($role, ['director', 'regionaldirector', 'regional director'])) {
                    $ctoRequest->rd_status = 'rejected';
                    $ctoRequest->approved_by_regionaldirector = $employeeId;
                    $ctoRequest->rd_remarks = $validated['remarks'];
                }
            } else {
                if ($role === 'chief') {
                    $ctoRequest->chief_status = 'approved';
                    $ctoRequest->approved_by_chief = $employeeId;
                    $ctoRequest->chief_remarks = $validated['remarks'];
                } elseif (in_array($role, ['hrstaff', 'hr staff', 'admin']) && $ctoRequest->chief_status === 'approved') {
                    $ctoRequest->hrstaff_status = 'approved';
                    $ctoRequest->approved_by_hrstaff = $employeeId;
                    $ctoRequest->hrstaff_remarks = $validated['remarks'];
                } elseif (in_array($role, ['director', 'regionaldirector', 'regional director']) && $ctoRequest->hrstaff_status === 'approved') {
                    $ctoRequest->rd_status = 'approved';
                    $ctoRequest->approved_by_regionaldirector = $employeeId;
                    $ctoRequest->rd_remarks = $validated['remarks'];
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

        $msg = $validated['status'] === 'approved' ? 'CTO request approved successfully.' : 'CTO request rejected.';

        return redirect()->route('hr.cto.index', ['tab' => 'pending'])->with('success', $msg);
    }
}
