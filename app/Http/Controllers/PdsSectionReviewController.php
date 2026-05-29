<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class PdsSectionReviewController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'section_name' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        $employee->pdsSectionReviews()->updateOrCreate(
            ['section_name' => $validated['section_name']],
            [
                'status' => $validated['status'],
                'remarks' => $validated['remarks'],
                'reviewed_by_id' => auth()->id(),
            ]
        );

        return back()->with('success', 'Review for ' . $validated['section_name'] . ' saved successfully.');
    }
}
