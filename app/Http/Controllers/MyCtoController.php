<?php

namespace App\Http\Controllers;

use App\Models\CtoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MyCtoController extends Controller
{
    public function index(): View
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            abort(404, 'Employee record not found.');
        }

        $ctoRequests = $employee->ctoRequests()->latest()->get();

        return view('my-cto.index', compact('employee', 'ctoRequests'));
    }

    public function create(): View
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            abort(404, 'Employee record not found.');
        }

        return view('my-cto.create', compact('employee'));
    }

    public function store(Request $request): RedirectResponse
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'type' => 'required|in:earn,use',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'hours' => 'required|numeric|min:0.5|max:999',
            'purpose' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:5120',
        ]);

        if ($validated['type'] === 'use' && $employee->cto_balance < $validated['hours']) {
            return back()->withInput()->withErrors([
                'hours' => 'Insufficient CTO balance. Available: '.$employee->cto_balance.' hour(s).',
            ]);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('cto-attachments', 'public');
        }

        $employee->ctoRequests()->create([
            'type' => $validated['type'],
            'date_start' => $validated['date_start'],
            'date_end' => $validated['date_end'],
            'hours' => $validated['hours'],
            'purpose' => $validated['purpose'],
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()->route('my-cto.index')->with('success', 'CTO request submitted successfully.');
    }

    public function show(CtoRequest $ctoRequest): View
    {
        $employee = auth()->user()->employee;

        if ($ctoRequest->employee_id !== $employee?->id
            && ! in_array(auth()->user()->role, ['admin', 'hrstaff', 'chief'])) {
            abort(403);
        }

        $ctoRequest->load(['employee', 'chief', 'hrstaff']);

        return view('my-cto.show', compact('ctoRequest'));
    }
}
