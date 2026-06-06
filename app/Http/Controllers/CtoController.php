<?php

namespace App\Http\Controllers;

use App\Models\CtoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CtoController extends Controller
{
    public function adminIndex(Request $request): View
    {
        $allCtoRequests = CtoRequest::with('employee')
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->get();

        $pendingCtoRequests = CtoRequest::with('employee')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $tab = $request->query('tab', 'pending');
        if (! in_array($tab, ['all', 'pending'])) {
            $tab = 'pending';
        }

        return view('my-cto.admin-index', compact('allCtoRequests', 'pendingCtoRequests', 'tab'));
    }

    public function updateStatus(Request $request, CtoRequest $ctoRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        $role = auth()->user()->role;
        $employeeId = auth()->user()->employee?->id;

        DB::transaction(function () use ($validated, $ctoRequest, $role, $employeeId) {
            if ($validated['status'] === 'rejected') {
                $ctoRequest->status = 'rejected';

                if ($role === 'chief') {
                    $ctoRequest->chief_status = 'rejected';
                    $ctoRequest->approved_by_chief = $employeeId;
                    $ctoRequest->chief_remarks = $validated['remarks'];
                } elseif (in_array($role, ['hrstaff', 'admin'])) {
                    $ctoRequest->hrstaff_status = 'rejected';
                    $ctoRequest->approved_by_hrstaff = $employeeId;
                    $ctoRequest->hrstaff_remarks = $validated['remarks'];
                }
            } else {
                if ($role === 'chief') {
                    $ctoRequest->chief_status = 'approved';
                    $ctoRequest->approved_by_chief = $employeeId;
                    $ctoRequest->chief_remarks = $validated['remarks'];
                } elseif (in_array($role, ['hrstaff', 'admin'])) {
                    $ctoRequest->hrstaff_status = 'approved';
                    $ctoRequest->approved_by_hrstaff = $employeeId;
                    $ctoRequest->hrstaff_remarks = $validated['remarks'];
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
