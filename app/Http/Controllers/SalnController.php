<?php

namespace App\Http\Controllers;

use App\Models\Saln;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalnController extends Controller
{
    public function index()
    {
        $salns = Auth::user()->employee->salns()->orderBy('as_of_date', 'desc')->get();
        return view('saln.index', compact('salns'));
    }

    public function create()
    {
        return view('saln.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_of_filing' => 'required|string',
            'as_of_date' => 'required|date',
            'declarant_info' => 'required|array',
            'spouse_info' => 'nullable|array',
            'filing_status' => 'required|string',
            'children' => 'nullable|array',
            'real_properties' => 'nullable|array',
            'personal_properties' => 'nullable|array',
            'liabilities' => 'nullable|array',
            'has_business_interests' => 'required|boolean',
            'business_interests' => 'nullable|array',
            'has_relatives_in_gov' => 'required|boolean',
            'relatives_in_gov' => 'nullable|array',
        ]);

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

        $saln = Auth::user()->employee->salns()->create(array_merge($validated, [
            'total_assets' => $total_assets,
            'total_liabilities' => $total_liabilities,
            'net_worth' => $net_worth,
        ]));

        return redirect()->route('salns.show', $saln)->with('success', 'SALN created successfully.');
    }

    public function show(Saln $saln)
    {
        if ($saln->employee_id !== Auth::user()->employee->id) {
            abort(403);
        }
        return view('saln.show', compact('saln'));
    }
}
