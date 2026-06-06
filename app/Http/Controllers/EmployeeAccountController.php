<?php

namespace App\Http\Controllers;

use App\Mail\AccountApprovedMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
     * Display the specified employee account.
     */
    public function show(User $user): View
    {
        $user->load('employee');

        return view('employees.accounts-show', compact('user'));
    }

    /**
     * Update the specified employee account details.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        if ($user->employee) {
            $user->employee->update($validated);
        }

        return redirect()->route('employee-accounts.show', $user)->with('success', 'Employee data updated successfully.');
    }

    /**
     * Approve an employee account.
     */
    public function approve(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'account_role' => ['required', 'string', 'in:employee,hrstaff,chief,regionaldirector,admin'],
        ]);

        if (! $user->employee) {
            return back()->with('error', 'Account could not be approved because the employee record is missing.');
        }

        $user->employee->update([
            'account_role' => $validated['account_role'],
        ]);

        $user->update(['is_approved' => true]);

        // Notify the user that their account has been approved
        Mail::to($user->email)->send(new AccountApprovedMail($user->name));

        return back()->with('success', 'Account for '.$user->display_name.' has been approved successfully.');
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

        return back()->with('success', 'Account for '.$displayName.' has been rejected and removed.');
    }
}
