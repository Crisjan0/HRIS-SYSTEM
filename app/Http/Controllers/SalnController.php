<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalnRequest;
use App\Models\Saln;
use App\Services\SalnPdfExporter;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SalnController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'User is not linked to an employee record.');
        }

        $salns = $employee->salns()->orderBy('as_of_date', 'desc')->get();
        $selectedYear = (int) request('year', now()->year);
        $selectedSaln = $salns->first(fn (Saln $saln) => (int) $saln->as_of_date->format('Y') === $selectedYear);
        $isSalnIndex = true;

        return view('saln.create', compact('employee', 'salns', 'selectedYear', 'selectedSaln', 'isSalnIndex'));
    }

    public function create()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'User is not linked to an employee record.');
        }

        $salns = $employee->salns()->orderBy('as_of_date', 'desc')->get();
        $selectedYear = (int) now()->year;
        $selectedSaln = null;
        $isSalnIndex = false;

        return view('saln.create', compact('employee', 'salns', 'selectedYear', 'selectedSaln', 'isSalnIndex'));
    }

    public function store(StoreSalnRequest $request)
    {
        $validated = $request->validated();
        $validated['has_business_interests'] = $request->boolean('has_business_interests');
        $validated['has_relatives_in_gov'] = $request->boolean('has_relatives_in_gov');

        // Calculate Totals
        $total_assets = 0;
        if (!empty($validated['real_properties'])) {
            foreach ($validated['real_properties'] as $prop) {
                $total_assets += (float) ($prop['acquisition_cost'] ?? 0);
            }
        }
        if (!empty($validated['personal_properties'])) {
            foreach ($validated['personal_properties'] as $prop) {
                $total_assets += (float) ($prop['acquisition_cost'] ?? 0);
            }
        }

        $total_liabilities = 0;
        if (!empty($validated['liabilities'])) {
            foreach ($validated['liabilities'] as $liab) {
                $total_liabilities += (float) ($liab['outstanding_balance'] ?? 0);
            }
        }

        $net_worth = $total_assets - $total_liabilities;

        $employee = Auth::user()->employee;
        $payload = array_merge($validated, [
            'employee_id' => $employee->id,
            'total_assets' => $total_assets,
            'total_liabilities' => $total_liabilities,
            'net_worth' => $net_worth,
        ]);

        $saln = null;
        if ($request->filled('saln_id')) {
            $saln = Saln::where('employee_id', $employee->id)
                ->findOrFail($request->integer('saln_id'));
            $saln->update($payload);
        } else {
            $saln = Saln::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'as_of_date' => $validated['as_of_date'],
                ],
                $payload
            );
        }

        return redirect()
            ->route('salns.index', ['year' => $saln->as_of_date->format('Y')])
            ->with('success', 'SALN saved successfully.');
    }

    public function show(Saln $saln)
    {
        if ($saln->employee_id !== Auth::user()->employee->id) {
            abort(403);
        }
        return view('saln.show', compact('saln'));
    }

    public function download(Saln $saln, SalnPdfExporter $exporter): Response|RedirectResponse
    {
        $employee = Auth::user()->employee;

        if (! $employee || $saln->employee_id !== $employee->id) {
            abort(403);
        }

        try {
            return $exporter->download($saln, request()->boolean('inline'));
        } catch (\Throwable $e) {
            return redirect()
                ->route('salns.show', $saln)
                ->with('error', 'Could not generate PDF. '.$e->getMessage());
        }
    }
}
