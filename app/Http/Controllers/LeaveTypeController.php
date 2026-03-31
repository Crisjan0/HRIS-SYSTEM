<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LeaveType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $leaveTypes = LeaveType::latest()->get();
        return view('leave-types.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('leave-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'description' => 'nullable|string',
            'days_per_year' => 'nullable|integer|min:0',
        ]);

        LeaveType::create($validated);

        return redirect()->route('leave-types.index')->with('success', 'Leave type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveType $leaveType)
    {
        return redirect()->route('leave-types.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveType $leaveType): View
    {
        return view('leave-types.edit', compact('leaveType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveType $leaveType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $leaveType->id,
            'description' => 'nullable|string',
            'days_per_year' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $leaveType->update($validated);

        return redirect()->route('leave-types.index')->with('success', 'Leave type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leaveType): RedirectResponse
    {
        $leaveType->delete();

        return redirect()->route('leave-types.index')->with('success', 'Leave type deleted successfully.');
    }
}
