@forelse($orders as $order)
    @php
        $statusColors = [
            'pending' => 'border-orange-100 bg-orange-50 text-orange-700',
            'approved' => 'border-green-100 bg-green-50 text-green-700',
            'rejected' => 'border-red-100 bg-red-50 text-red-700',
        ];
        $statusColor = $statusColors[$order->status] ?? 'border-gray-100 bg-gray-50 text-gray-600';
        $employeeName = trim(($order->employee?->firstname ?? '') . ' ' . ($order->employee?->lastname ?? ''));
        $employeePosition = $order->employee?->position ?: __('No position');
        $searchText = Str::lower($employeeName . ' ' . ($order->employee?->position ?? '') . ' ' . ($order->employee?->division ?? '') . ' ' . $order->places_of_travel . ' ' . $order->purpose . ' ' . $order->travel_type_label . ' ' . $order->status);
        $stages = [
            ['label' => 'HR', 'status' => $order->hrstaff_status],
            ['label' => 'Chief', 'status' => $order->chief_status],
            ['label' => 'Regional Director', 'status' => $order->rd_status],
        ];
        $travelTrackerPayload = [
            'id' => (string) $order->id,
            'title' => $employeeName,
            'stages' => collect($stages)->map(fn ($stage) => [
                'label' => $stage['label'],
                'status' => $stage['status'] ?: 'pending',
            ])->values(),
        ];
    @endphp
    <tr class="transition-colors hover:bg-gray-50/70"
        data-approval-row="{{ $order->id }}"
        data-manage-travel-row="{{ $order->status === 'pending' ? 'pending' : 'all' }}"
        data-search="{{ $searchText }}"
        data-type="{{ $order->travel_type }}"
        data-employee="{{ Str::lower($employeeName) }}"
        data-filed="{{ $order->created_at?->timestamp ?? 0 }}">
        <td class="px-3 py-2.5 align-middle">
            <button type="button" @click="$dispatch('travel-selected', @js($travelTrackerPayload))" class="block w-full truncate whitespace-nowrap text-left text-sm font-bold text-blue-900 underline-offset-4 transition hover:text-blue-700 hover:underline" title="{{ __('Show approval progress for ') }}{{ $employeeName }}">
                {{ $order->employee?->firstname }} {{ $order->employee?->lastname }}
            </button>
            <div class="truncate whitespace-nowrap text-xs text-gray-400" title="{{ $employeePosition }}">
                {{ $employeePosition }}
            </div>
        </td>
        <td class="px-3 py-2.5 align-middle">
            <div class="truncate whitespace-nowrap text-sm font-bold text-gray-900" title="{{ $order->places_of_travel }}">
                {{ $order->places_of_travel }}
            </div>
        </td>
        <td class="px-3 py-2.5 align-middle">
            <div class="whitespace-nowrap text-sm font-medium text-gray-700">
                {{ $order->created_at?->format('M d, Y') }}
            </div>
        </td>
        <td class="px-3 py-2.5 text-right align-middle">
            <div class="flex items-center justify-end gap-1.5">
                <a href="{{ route('travel-orders.show', $order) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-700 transition-colors hover:bg-indigo-50 hover:text-indigo-900" title="{{ __('View details') }}" aria-label="{{ __('View details') }}">
                    <i class="fa-solid fa-eye"></i>
                </a>
                @if(($actionMode ?? 'view') === 'review')
                    @php
                        $role = strtolower(auth()->user()->role ?? '');
                        $isRD = $role === 'regionaldirector';
                    @endphp
                    @if($isRD)
                        <form action="{{ route('travel-orders.update-status', $order->id) }}" method="POST" onsubmit="return confirm('{{ __('Approve this travel authority?') }}');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-green-600 transition-colors hover:bg-green-50 hover:text-green-800" title="{{ __('Approve') }}" aria-label="{{ __('Approve') }}">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>
                        <form action="{{ route('travel-orders.update-status', $order->id) }}" method="POST" id="taRejectRowForm_{{ $order->id }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <input type="hidden" name="remarks" id="taRejectRowRemarks_{{ $order->id }}" value="">
                            <button type="button" onclick="submitTaRejectRow({{ $order->id }})" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition-colors hover:bg-red-50 hover:text-red-800" title="{{ __('Decline') }}" aria-label="{{ __('Decline') }}">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                    @endif
                @endif
            </div>
            @if(($actionMode ?? 'view') === 'review' && ($isRD ?? false))
                <script>
                    if (typeof window.submitTaRejectRow === 'undefined') {
                        window.submitTaRejectRow = function(id) {
                            if (!confirm('Are you sure you want to decline this travel authority?')) {
                                return;
                            }
                            document.getElementById('taRejectRowForm_' + id).submit();
                        }
                    }
                </script>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="px-4 py-8 text-center">
            <div class="text-gray-400 mb-2">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-gray-500 italic font-medium">{{ $emptyMessage ?? __('No travel authorities found.') }}</p>
        </td>
    </tr>
@endforelse
