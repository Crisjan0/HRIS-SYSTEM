<?php

namespace App\Http\Controllers;

use App\Mail\HrisAccountCreatedMail;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private const DIVISIONS = [
        'Finance and Administrative Division',
        'Migrant Workers Processing Division',
        'Migrant Workers Protections Division',
        'Welfare and Reintegration Division',
    ];

    public function index(Request $request): View
    {
        $divisionOptions = self::DIVISIONS;
        $search = trim((string) $request->query('search', ''));
        $division = (string) $request->query('division', '');
        $sort = $request->query('sort', 'name_asc');

        $employees = $this->employeeIndexQuery($search, $division, $sort)->get();
        $archivedEmployees = Employee::onlyTrashed()->with('user')->latest('deleted_at')->get();
        $pendingAccounts = User::query()
            ->with('employee')
            ->whereNotNull('email_verified_at')
            ->where('is_approved', false)
            ->where('account_status', '!=', 'disabled')
            ->latest()
            ->get();

        $approvedAccounts = User::query()
            ->with('employee')
            ->whereNotNull('email_verified_at')
            ->where('is_approved', true)
            ->where('account_status', 'active')
            ->latest()
            ->get();

        return view('employees.index', compact('employees', 'archivedEmployees', 'pendingAccounts', 'approvedAccounts', 'divisionOptions', 'search', 'division', 'sort'));
    }

    public function filter(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $division = (string) $request->query('division', '');
        $sort = $request->query('sort', 'name_asc');
        $employees = $this->employeeIndexQuery($search, $division, $sort)->get();

        return response()->json([
            'html' => view('employees.partials.rows', compact('employees'))->render(),
            'count' => $employees->count(),
        ]);
    }

    private function employeeIndexQuery(string $search, string $division, string $sort)
    {
        $query = Employee::with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('middlename', 'like', "%{$search}%")
                        ->orWhere('rfid_number', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%")
                        ->orWhere('division', 'like', "%{$search}%");
                });
            })
            ->when(in_array($division, self::DIVISIONS, true), fn ($query) => $query->where('division', $division));

        return match ($sort) {
            'name_desc' => $query->orderByDesc('lastname')->orderByDesc('firstname'),
            'division_asc' => $query->orderBy('division')->orderBy('lastname')->orderBy('firstname'),
            'newest' => $query->latest(),
            default => $query->orderBy('lastname')->orderBy('firstname'),
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::whereDoesntHave('employee')->get();
        $roles = ['employee', 'hrstaff', 'chief', 'regionaldirector', 'admin'];
        $divisionOptions = self::DIVISIONS;
        $employmentStatuses = ['Regular', 'Job Order', 'Contract of Service'];

        return view('employees.create', compact('users', 'roles', 'divisionOptions', 'employmentStatuses'));
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
            'notification_email' => 'nullable|required_with:account_email|email|max:255',
            'division' => 'required|string|in:'.implode(',', self::DIVISIONS),
            'position' => 'required|string|max:255',
            'account_role' => 'required|string|in:employee,hrstaff,chief,regionaldirector,admin',
            'employment_status' => 'nullable|string|in:Regular,Job Order,Contract of Service',
            'user_id' => 'nullable|exists:users,id|unique:employees,user_id',
            'account_email' => 'nullable|email|max:255|ends_with:@dmw.gov.ph|unique:users,email',
            'rfid_number' => 'nullable|string|max:255|unique:employees,rfid_number',
        ]);

        if (! empty($validated['user_id']) && ! empty($validated['account_email'])) {
            return back()
                ->withErrors(['account_email' => 'Choose either a linked user account or create a new login account, not both.'])
                ->withInput();
        }

        $createdAccount = null;
        $temporaryPassword = null;
        $notificationEmail = null;

        DB::transaction(function () use (&$validated, &$createdAccount, &$temporaryPassword, &$notificationEmail) {
            if (! empty($validated['account_email'])) {
                $temporaryPassword = Str::password(12, true, true, false, false);
                $notificationEmail = $validated['notification_email'];
                $user = User::create([
                    'name' => trim($validated['firstname'].' '.$validated['lastname']),
                    'email' => $validated['account_email'],
                    'password' => $temporaryPassword,
                    'email_verified_at' => now(),
                    'is_approved' => true,
                    'must_change_password' => true,
                    'account_status' => 'active',
                    'privacy_consent' => true,
                ]);

                $validated['user_id'] = $user->id;
                $createdAccount = $user;
            }

            unset($validated['account_email']);

            Employee::create($validated);
        });

        if ($createdAccount && $temporaryPassword) {
            try {
                Mail::to($notificationEmail)->send(new HrisAccountCreatedMail(
                    $createdAccount->name,
                    $createdAccount->email,
                    $temporaryPassword,
                ));
            } catch (Throwable $exception) {
                Log::warning('Unable to send HRIS account creation email.', [
                    'user_id' => $createdAccount->id,
                    'email' => $createdAccount->email,
                    'notification_email' => $notificationEmail,
                    'message' => $exception->getMessage(),
                ]);

                return redirect()->route('employees.index')
                    ->with('success', 'Employee created successfully.')
                    ->with('error', 'The login account was created, but the account email could not be sent. Please check the mail settings.');
            }
        }

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee): View
    {
        $employee->ensureLeaveCredits(now()->year);

        $employee->load([
            'pdsPersonal', 'pdsFamily', 'pdsChildren', 'pdsEducation', 
            'pdsEligibilities', 'pdsWorkExperiences', 'pdsVoluntaryWorks', 
            'pdsTrainings', 'pdsOthers', 'pdsQuestionnaire', 'pdsReferences', 'pdsGovId',
            'pdsSectionReviews', 'salns', 'leaveCredits.leaveType'
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
        $roles = ['employee', 'hrstaff', 'chief', 'regionaldirector', 'admin'];
        $divisionOptions = self::DIVISIONS;
        $employmentStatuses = ['Regular', 'Job Order', 'Contract of Service'];

        return view('employees.edit', compact('employee', 'users', 'roles', 'divisionOptions', 'employmentStatuses'));
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
            'notification_email' => 'nullable|email|max:255',
            'division' => 'required|string|in:'.implode(',', self::DIVISIONS),
            'position' => 'required|string|max:255',
            'account_role' => 'required|string|in:employee,hrstaff,chief,regionaldirector,admin',
            'employment_status' => 'nullable|string|in:Regular,Job Order,Contract of Service',
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
        DB::transaction(function () use ($employee) {
            if ($employee->user) {
                $employee->user->update(['account_status' => 'disabled']);
            }

            $employee->delete();
        });

        return redirect()->route('employees.index')->with('success', 'Employee archived and login access disabled successfully.');
    }

    public function restore(int $employee): RedirectResponse
    {
        $employee = Employee::onlyTrashed()->with('user')->findOrFail($employee);

        DB::transaction(function () use ($employee) {
            $employee->restore();

            if ($employee->user) {
                $employee->user->update([
                    'account_status' => 'active',
                    'is_approved' => true,
                ]);
            }
        });

        return redirect()->route('employees.index')->with('success', 'Employee restored and login access re-enabled successfully.');
    }

    public function forceDelete(int $employee): RedirectResponse
    {
        $employee = Employee::onlyTrashed()->with('user')->findOrFail($employee);

        DB::transaction(function () use ($employee) {
            $user = $employee->user;

            $this->deleteProfilePicture($employee->profile_picture);
            $employee->forceDelete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('employees.index')->with('success', 'Employee record and linked account permanently deleted.');
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
