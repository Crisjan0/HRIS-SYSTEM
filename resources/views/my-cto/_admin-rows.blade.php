@forelse($requests as $request)
    @php
        $statusColors = [
            'pending' => 'text-orange-700 bg-orange-50 border-orange-100',
            'approved' => 'text-green-600 bg-green-50 border-green-100',
            'rejected' => 'text-red-600 bg-red-50 border-red-100',
        ];
        $statusColor = $statusColors[$request->status] ?? 'text-gray-600 bg-gray-50 border-gray-100';

        $typeColors = [
            'earn' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
            'use' => 'text-blue-600 bg-blue-50 border-blue-100',
        ];
        $typeColor = $typeColors[$request->type] ?? 'text-gray-600 bg-gray-50 border-gray-100';
        $employeeName = trim(($request->employee->firstname ?? '') . ' ' . ($request->employee->lastname ?? ''));
        $employeePosition = $request->employee->position ?: __('No position');
        $searchText = Str::lower($employeeName . ' ' . ($request->employee->position ?? '') . ' ' . ($request->employee->division ?? '') . ' ' . $request->type_label . ' ' . $request->purpose . ' ' . $request->status);
        $stages = [
            ['label' => 'Chief', 'status' => $request->chief_status],
            ['label' => 'HR', 'status' => $request->hrstaff_status],
            ['label' => 'Director', 'status' => $request->rd_status],
        ];
        $ctoTrackerPayload = [
            'id' => (string) $request->id,
            'title' => $employeeName,
            'stages' => collect($stages)->map(fn ($stage) => [
                'label' => $stage['label'],
                'status' => $stage['status'] ?: 'pending',
            ])->values(),
        ];
    @endphp
    <tr class="transition-colors hover:bg-gray-50/70"
        data-approval-row="{{ $request->id }}"
        data-manage-cto-row="{{ $request->status === 'pending' ? 'pending' : 'all' }}"
        data-search="{{ $searchText }}"
        data-type="{{ $request->type }}"
        data-employee="{{ Str::lower($employeeName) }}"
        data-filed="{{ $request->created_at?->timestamp ?? 0 }}">
        <td class="px-4 py-3">
            <div class="flex items-center gap-3 min-w-0">
                <x-profile-avatar :employee="$request->employee" size="sm" variant="indigo" rounded="2xl" />
                <div class="min-w-0">
                    <div class="text-sm font-bold text-gray-900 truncate">
                        <button type="button" @click="$dispatch('cto-selected', @js($ctoTrackerPayload))" class="truncate text-left font-bold text-blue-900 underline-offset-4 transition hover:text-blue-700 hover:underline" title="{{ __('Show approval progress for ') }}{{ $employeeName }}">
                            {{ $request->employee->firstname }} {{ $request->employee->lastname }}
                        </button>
                    </div>
                    <div class="text-xs text-gray-400 truncate">
                        {{ $employeePosition }}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-4 py-3">
            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-[10px] font-black uppercase tracking-widest {{ $typeColor }}">
                {{ $request->type_label }}
            </span>
        </td>
        <td class="px-4 py-3 text-sm font-medium text-gray-700">
            {{ $request->created_at?->format('M d, Y') }}
        </td>
        <td class="px-4 py-3">
            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-[10px] font-black uppercase tracking-widest {{ $statusColor }}">
                {{ ucfirst($request->status) }}
            </span>
        </td>
        <td class="px-4 py-3 text-right">
            <a href="{{ route('my-cto.show', $request) }}"
               class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-700 transition-colors hover:bg-indigo-50 hover:text-indigo-900"
               title="{{ __('View CTO Request') }}"
               aria-label="{{ __('View CTO Request') }}">
                <i class="fa-solid fa-eye"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-4 py-10 text-center text-sm font-medium italic text-gray-500">
            {{ $emptyMessage }}
        </td>
    </tr>
@endforelse
