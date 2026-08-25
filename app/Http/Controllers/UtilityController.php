<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\UtilityOption;
use App\Support\UtilityOptionRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UtilityController extends Controller
{
    public function index(Request $request): View
    {
        UtilityOptionRegistry::ensureDefaults();

        $tabs = UtilityOptionRegistry::tabs();
        $tab = $request->string('tab')->toString() ?: 'leave-types';
        if (! array_key_exists($tab, $tabs)) {
            $tab = 'leave-types';
        }

        $groups = UtilityOptionRegistry::groups();
        $leaveTypes = LeaveType::query()->latest()->get();
        $optionsByGroup = UtilityOption::query()
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get()
            ->groupBy('group_key');

        return view('utilities.index', compact('tab', 'tabs', 'groups', 'leaveTypes', 'optionsByGroup'));
    }

    public function storeOption(Request $request): RedirectResponse
    {
        $groups = UtilityOptionRegistry::groups();

        $validated = $request->validate([
            'group_key' => ['required', 'string'],
            'label' => ['required', 'string', 'max:255'],
            'value' => ['nullable', 'string', 'max:255'],
            'parent_value' => ['nullable', 'string', 'max:255'],
        ]);

        abort_unless(array_key_exists($validated['group_key'], $groups), 404);

        $nextSort = ((int) UtilityOption::query()->where('group_key', $validated['group_key'])->max('sort_order')) + 1;

        UtilityOption::create([
            'group_key' => $validated['group_key'],
            'label' => $validated['label'],
            'value' => ($validated['value'] ?? null) ?: $validated['label'],
            'parent_group' => $groups[$validated['group_key']]['parent_group'] ?? null,
            'parent_value' => $validated['parent_value'] ?? null,
            'sort_order' => $nextSort,
            'is_active' => true,
        ]);

        return redirect()
            ->route('utilities.index', ['tab' => $validated['group_key']])
            ->with('success', 'Utility option added.');
    }

    public function updateOption(Request $request, UtilityOption $utilityOption): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'value' => ['nullable', 'string', 'max:255'],
            'parent_value' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $utilityOption->update([
            'label' => $validated['label'],
            'value' => ($validated['value'] ?? null) ?: $validated['label'],
            'parent_value' => $validated['parent_value'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('utilities.index', ['tab' => $utilityOption->group_key])
            ->with('success', 'Utility option updated.');
    }

    public function destroyOption(UtilityOption $utilityOption): RedirectResponse
    {
        $tab = $utilityOption->group_key;
        $utilityOption->delete();

        return redirect()
            ->route('utilities.index', ['tab' => $tab])
            ->with('success', 'Utility option removed.');
    }
}
