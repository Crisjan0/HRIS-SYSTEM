<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $holidays = Holiday::orderBy('date')->get();
        return view('holidays.index', compact('holidays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date',
        ]);

        Holiday::create($validated);

        return redirect()->route('holidays.index')->with('success', 'Holiday added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Holiday $holiday): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date,' . $holiday->id,
        ]);

        $holiday->update($validated);

        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday): RedirectResponse
    {
        $holiday->delete();

        return redirect()->route('holidays.index')->with('success', 'Holiday deleted successfully.');
    }
}
