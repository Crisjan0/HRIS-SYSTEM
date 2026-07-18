@forelse($leaves as $leaf)
    @php
        $stages = [
            ['label' => 'Chief', 'status' => $leaf->chief_status],
            ['label' => 'HR', 'status' => $leaf->hrstaff_status],
            ['label' => 'Director', 'status' => $leaf->rd_status],
        ];
        $approvedCount = collect($stages)->where('status', 'approved')->count();
        $hasRejected = collect($stages)->contains(fn ($stage) => $stage['status'] === 'rejected');
        $displayStatus = $hasRejected ? 'Rejected' : ($approvedCount === 3 ? 'Approved' : 'Pending');
        $statusClass = $hasRejected
            ? 'border-red-100 bg-red-50 text-red-700'
            : ($approvedCount === 3 ? 'border-green-100 bg-green-50 text-green-700' : 'border-orange-100 bg-orange-50 text-orange-700');
        $leaveTypeName = Str::of($leaf->leaveType?->name ?? '')->replaceMatches('/\s+Leave\b/i', '')->trim();
        $employeePosition = $leaf->employee?->position ?: __('No position');
        $actionTitle = ($actionMode ?? 'view') === 'review' ? __('Review application') : __('View details');
        $leaveTrackerPayload = [
            'id' => (string) $leaf->id,
            'title' => trim(($leaf->employee?->firstname ?? '') . ' ' . ($leaf->employee?->lastname ?? '')),
            'employee' => trim(($leaf->employee?->firstname ?? '') . ' ' . ($leaf->employee?->lastname ?? '')),
            'type' => (string) $leaveTypeName,
            'stages' => collect($stages)->map(fn ($stage) => [
                'label' => $stage['label'],
                'status' => $stage['status'] ?: 'pending',
            ])->values(),
        ];
    @endphp
    <tr class="transition-colors hover:bg-gray-50/70" data-approval-row="{{ $leaf->id }}">
        <td class="px-4 py-3 align-middle">
            <button type="button" @click="$dispatch('leave-selected', @js($leaveTrackerPayload))" class="w-full min-w-0 overflow-hidden text-left text-sm font-bold text-blue-900 underline-offset-4 transition hover:text-blue-700 hover:underline" title="{{ __('Show approval process for ') }}{{ $leaf->employee?->firstname }} {{ $leaf->employee?->lastname }}">
                <span class="block truncate">{{ $leaf->employee?->firstname }} {{ $leaf->employee?->lastname }}</span>
            </button>
            <div class="truncate whitespace-nowrap text-xs text-gray-400" title="{{ $employeePosition }}">
                {{ $employeePosition }}
            </div>
        </td>
        <td class="px-4 py-3 align-middle">
            <div class="w-full min-w-0 overflow-hidden text-ellipsis whitespace-nowrap text-sm font-bold text-gray-900" title="{{ $leaveTypeName }}">{{ $leaveTypeName }}</div>
        </td>
        <td class="px-4 py-3 align-middle">
            <div class="whitespace-nowrap text-sm font-medium text-gray-700">
                {{ \Carbon\Carbon::parse($leaf->date_filed)->format('M d, Y') }}
            </div>
        </td>
        <td class="px-4 py-3 align-middle">
            <span class="inline-flex rounded-full border px-2.5 py-1 text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">
                {{ $displayStatus }}
            </span>
        </td>
        <td class="px-4 py-3 text-right align-middle">
            <div class="flex items-center justify-end gap-1.5">
                <a href="{{ route('leave-applications.show', $leaf->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-700 transition-colors hover:bg-indigo-50 hover:text-indigo-900" title="{{ $actionTitle }}" aria-label="{{ $actionTitle }}">
                    <i class="fa-solid fa-eye"></i>
                </a>
                @if(($actionMode ?? 'view') === 'review')
                    <form action="{{ route('leave-applications.update', $leaf->id) }}" method="POST" onsubmit="return confirm('{{ __('Approve this leave application?') }}');">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-green-600 transition-colors hover:bg-green-50 hover:text-green-800" title="{{ __('Approve') }}" aria-label="{{ __('Approve') }}">
                            <i class="fa-solid fa-check"></i>
                        </button>
                    </form>
                    <form action="{{ route('leave-applications.update', $leaf->id) }}" method="POST" onsubmit="return confirm('{{ __('Decline this leave application?') }}');">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition-colors hover:bg-red-50 hover:text-red-800" title="{{ __('Decline') }}" aria-label="{{ __('Decline') }}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </form>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-4 py-12 text-center">
            <div class="text-gray-400 mb-2">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-gray-500 italic font-medium">
                {{ $emptyMessage ?? __('No leave applications found.') }}
            </p>
        </td>
    </tr>
@endforelse
