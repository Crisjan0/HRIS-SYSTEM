<x-app-layout>
    <x-slot name="title">Utilities</x-slot>

    @php
        $currentMeta = $groups[$tab] ?? null;
        $groupOptions = $currentMeta ? $optionsByGroup->get($tab, collect()) : collect();
        $parentGroup = $currentMeta['parent_group'] ?? null;
        $parentOptions = $parentGroup ? $optionsByGroup->get($parentGroup, collect()) : collect();
    @endphp

    <div class="p-4 sm:p-6 lg:p-8">
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white/80 shadow-lg backdrop-blur-xl">
            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-2xl font-bold text-gray-800">Utilities</h2>
                <p class="mt-1 text-sm text-gray-500">Manage fixed dropdown values used across leave, PDS, and SALN.</p>
            </div>

            @if(session('success'))
                <div class="border-b border-emerald-100 bg-emerald-50 px-6 py-3 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="border-b border-gray-100 px-6 py-4">
                <div class="overflow-x-auto">
                    <div class="flex w-max min-w-full items-center gap-2">
                        @foreach($tabs as $tabKey => $tabLabel)
                            <a
                                href="{{ route('utilities.index', ['tab' => $tabKey]) }}"
                                class="shrink-0 whitespace-nowrap rounded-[6px] px-4 py-2 text-xs font-black uppercase tracking-[0.14em] transition
                                    {{ $tab === $tabKey
                                        ? 'bg-blue-50 text-[#0038a8]'
                                        : 'bg-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}"
                            >
                                {{ $tabLabel }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-8 p-6">
                @if($tab === 'leave-types')
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Leave Types</h3>
                            <p class="mt-1 text-sm text-slate-500">These values are managed in the existing leave types module.</p>
                        </div>
                        <a href="{{ route('leave-types.create') }}" class="inline-flex items-center rounded-lg bg-[#0038a8] px-4 py-2 text-xs font-black uppercase tracking-widest text-white hover:bg-[#002f8f]">
                            Add Leave Type
                        </a>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-black uppercase tracking-widest text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Leave Type</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Created</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($leaveTypes as $leaveType)
                                    <tr>
                                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $leaveType->name }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ ($leaveType->is_active ?? true) ? 'Active' : 'Inactive' }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ optional($leaveType->created_at)->format('M d, Y') }}</td>
                                        <td class="px-4 py-3"><a href="{{ route('leave-types.edit', $leaveType) }}" class="text-xs font-black uppercase tracking-widest text-[#0038a8] hover:text-[#002f8f]">Edit</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">No leave types found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <h3 class="text-sm font-black uppercase tracking-[0.14em] text-slate-700">{{ $currentMeta['label'] ?? $tabs[$tab] }}</h3>
                        </div>

                        <div class="space-y-5 p-5">
                            <form method="POST" action="{{ route('utilities.options.store') }}" class="grid gap-3 md:grid-cols-3">
                                @csrf
                                <input type="hidden" name="group_key" value="{{ $tab }}">
                                <div>
                                    <label class="mb-1 block text-[11px] font-black uppercase tracking-widest text-slate-400">Option</label>
                                    <input type="text" name="label" class="w-full rounded-lg border-slate-200 text-sm font-semibold" required>
                                </div>
                                <input type="hidden" name="value" value="">
                                @if($parentGroup)
                                    <div>
                                        <label class="mb-1 block text-[11px] font-black uppercase tracking-widest text-slate-400">Parent</label>
                                        <select name="parent_value" class="w-full rounded-lg border-slate-200 text-sm font-semibold">
                                            <option value="">No parent</option>
                                            @foreach($parentOptions as $parentOption)
                                                <option value="{{ $parentOption->value }}">{{ $parentOption->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="flex items-end justify-end">
                                    <button type="submit" class="inline-flex items-center rounded-lg bg-[#0038a8] px-4 py-2 text-xs font-black uppercase tracking-widest text-white hover:bg-[#002f8f]">Add Option</button>
                                </div>
                            </form>

                            <div class="space-y-3">
                                @forelse($groupOptions as $option)
                                    <div class="flex flex-col gap-3 rounded-xl border border-slate-200 p-4 lg:flex-row lg:items-center lg:justify-between">
                                        <form method="POST" action="{{ route('utilities.options.update', $option) }}" class="grid flex-1 gap-3 md:grid-cols-{{ $parentGroup ? '3' : '2' }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="label" value="{{ $option->label }}" class="rounded-lg border-slate-200 text-sm font-semibold">
                                            <input type="hidden" name="value" value="{{ $option->label }}">
                                            @if($parentGroup)
                                                <select name="parent_value" class="rounded-lg border-slate-200 text-sm font-semibold">
                                                    <option value="">No parent</option>
                                                    @foreach($parentOptions as $parentOption)
                                                        <option value="{{ $parentOption->value }}" @selected($option->parent_value === $parentOption->value)>{{ $parentOption->label }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            <div class="flex items-center gap-2">
                                                <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600">
                                                    <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-[#0038a8]" {{ $option->is_active ? 'checked' : '' }}>
                                                    Active
                                                </label>
                                                <button type="submit" class="rounded-lg bg-slate-100 px-4 py-2 text-xs font-black uppercase tracking-widest text-[#0038a8] hover:bg-slate-200">Save</button>
                                            </div>
                                        </form>

                                        <form method="POST" action="{{ route('utilities.options.destroy', $option) }}" onsubmit="return confirm('Remove this option?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-red-50 px-4 py-2 text-xs font-black uppercase tracking-widest text-red-600 hover:bg-red-100">Delete</button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="rounded-xl border border-dashed border-slate-300 px-4 py-6 text-center text-sm text-slate-500">No options yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>