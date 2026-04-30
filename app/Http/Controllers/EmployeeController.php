<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $employees = Employee::with('user')->get();

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::whereDoesntHave('employee')->get();
        $roles = ['user', 'employee', 'hrstaff', 'chief', 'regional director', 'admin'];

        return view('employees.create', compact('users', 'roles'));
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
            'role' => 'required|string',
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
            'pdsTrainings', 'pdsOthers', 'pdsQuestionnaire', 'pdsReferences', 'pdsGovId'
        ]);

        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee): View
    {
        $users = User::whereDoesntHave('employee', function ($query) use ($employee) {
            $query->where('id', '!=', $employee->id);
        })->get();
        $roles = ['user', 'employee', 'hrstaff', 'chief', 'regional director', 'admin'];

        return view('employees.edit', compact('employee', 'users', 'roles'));
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
            'role' => 'required|string',
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
}
