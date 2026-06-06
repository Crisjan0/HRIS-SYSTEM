<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $employees = Employee::with('user')->get();
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

        return view('employees.index', compact('employees', 'pendingAccounts', 'approvedAccounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::whereDoesntHave('employee')->get();
        $positions = ['employee', 'hrstaff', 'chief', 'regionaldirector', 'admin'];

        return view('employees.create', compact('users', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'position' => 'required|string',
            'user_id' => 'nullable|exists:users,id|unique:employees,user_id',
            'rfid_number' => 'nullable|string|max:255|unique:employees,rfid_number',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee): View
    {
        $employee->load([
            'pdsPersonal', 'pdsFamily', 'pdsChildren', 'pdsEducation', 
            'pdsEligibilities', 'pdsWorkExperiences', 'pdsVoluntaryWorks', 
            'pdsTrainings', 'pdsOthers', 'pdsQuestionnaire', 'pdsReferences', 'pdsGovId',
            'pdsSectionReviews', 'salns'
        ]);

        return view('employees.show', compact('employee'));
    }

    /**
     * Display the current logged in user's employee record.
     */
    public function myRecord(): View|\Illuminate\Http\RedirectResponse
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'You do not have an associated employee record.');
        }

        $employee->load([
            'pdsPersonal'
        ]);

        return view('employees.personal-information', compact('employee'));
    }

    /**
     * Show the form for editing the current logged in user's personal information.
     */
    public function editMyRecord(): View|\Illuminate\Http\RedirectResponse
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'You do not have an associated employee record.');
        }

        return view('employees.personal-information-edit', compact('employee'));
    }

    /**
     * Update the current logged in user's personal information.
     */
    public function updateMyRecord(Request $request): \Illuminate\Http\RedirectResponse
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'You do not have an associated employee record.');
        }

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
        ]);

        // Update employee name and contact number
        $employee->update([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'middlename' => $validated['middlename'],
            'contact_number' => $validated['contact_number']
        ]);

        // Update user name (combining first and last)
        $user = auth()->user();
        $user->update([
            'name' => trim($validated['firstname'] . ' ' . $validated['lastname']),
        ]);

        // If they have a PDS personal info record, sync the contact there too
        if ($employee->pdsPersonal) {
            $employee->pdsPersonal->update([
                'firstname' => $validated['firstname'],
                'surname' => $validated['lastname'],
                'middlename' => $validated['middlename'],
                'mobile_no' => $validated['contact_number'],
            ]);
        }

        return redirect()->route('personal-information.show')->with('success', 'Personal information updated successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee): View
    {
        $users = User::whereDoesntHave('employee', function ($query) use ($employee) {
            $query->where('id', '!=', $employee->id);
        })->get();
        $positions = ['employee', 'hrstaff', 'chief', 'regionaldirector', 'admin'];

        return view('employees.edit', compact('employee', 'users', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'position' => 'required|string',
            'user_id' => 'nullable|exists:users,id|unique:employees,user_id,' . $employee->id,
            'rfid_number' => 'nullable|string|max:255|unique:employees,rfid_number,' . $employee->id,
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    /**
     * Upload profile picture.
     */
    public function uploadProfilePicture(Request $request, Employee $employee): RedirectResponse
    {
        // Only allow admins, hrstaff, director, or the employee themselves to update it
        if (!in_array(auth()->user()->role, ['admin', 'hrstaff', 'director', 'chief', 'regionaldirector', 'regional director']) && auth()->user()->employee?->id !== $employee->id) {
            abort(403);
        }

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $this->deleteProfilePicture($employee->profile_picture);

            $path = $request->file('profile_picture')->store('profile_pictures', 'public_uploads');
            $employee->update(['profile_picture' => $path]);
        }

        return back()->with('success', 'Profile picture updated successfully.');
    }

    private function deleteProfilePicture(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (Storage::disk('public_uploads')->exists($path)) {
            Storage::disk('public_uploads')->delete($path);
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
