<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeAccountController extends Controller
{
    /**
     * Display a list of registered employee accounts pending HR approval.
     */
    public function index(): View
    {
        $pendingAccounts = User::query()
            ->with('employee')
            ->whereNotNull('email_verified_at')
            ->where('is_approved', false)
            ->latest()
            ->get();

        $approvedAccounts = User::query()
            ->with('employee')
            ->whereNotNull('email_verified_at')
            ->where('is_approved', true)
            ->latest()
            ->get();

        return view('employees.accounts', compact('pendingAccounts', 'approvedAccounts'));
    }

    /**
     * Approve an employee account.
     */
    public function approve(User $user): RedirectResponse
    {
        $user->update(['is_approved' => true]);

        return back()->with('success', 'Account for ' . $user->display_name . ' has been approved successfully.');
    }

    /**
     * Reject and delete an unapproved employee account.
     */
    public function reject(User $user): RedirectResponse
    {
        $displayName = $user->display_name;

        // Delete the associated employee record first
        if ($user->employee) {
            $user->employee->delete();
        }

        $user->delete();

        return back()->with('success', 'Account for ' . $displayName . ' has been rejected and removed.');
    }
}
