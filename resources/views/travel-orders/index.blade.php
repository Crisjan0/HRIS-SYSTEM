<x-app-layout>
    <x-slot name="title">{{ __('My Travel Orders') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $trackedOrder = $travelOrders->firstWhere('status', 'pending') ?? $travelOrders->first();
                $trackedOrderPayload = $trackedOrder ? [
                    'id' => (string) $trackedOrder->id,
                    'title' => $trackedOrder->places_of_travel,
                    'stages' => collect([
                        ['label' => 'Chief', 'status' => $trackedOrder->chief_status],
                        ['label' => 'HR', 'status' => $trackedOrder->hrstaff_status],
                        ['label' => 'Director', 'status' => $trackedOrder->rd_status],
                    ])->map(fn ($stage) => [
                        'label' => $stage['label'],
                        'status' => $stage['status'] ?: 'pending',
                    ])->values(),
                ] : null;
            @endphp

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-100 overflow-hidden" x-data="myTravelOrdersTable('{{ $trackedOrder?->id }}')" x-init="init()">
                <div class="p-6 flex justify-between items-center border-b border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('My Travel Orders') }}</h2>
                    <a href="{{ route('travel-orders.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-900 px-4 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('New Travel Order') }}
                    </a>
                </div>

                <div class="px-6 pt-5">
                    <x-approval-tracker :payload="$trackedOrderPayload" event="travel-selected" empty="No travel order to track yet." />
                </div>

                <div class="border-b border-gray-100 bg-gray-50/70 p-2">
                    <div class="flex min-w-0 flex-col gap-2 sm:flex-row sm:items-center">
                        <div class="relative min-w-0 sm:flex-1">
                            <label for="my_travel_search" class="sr-only">{{ __('Search travel order') }}</label>
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M21 21l-4.35-4.35M10.75 18.5a7.75 7.75 0 100-15.5 7.75 7.75 0 000 15.5z" />
                                </svg>
                            </span>
                            <input id="my_travel_search" type="search" x-model="search" @input.debounce.200ms="applyFilters()" placeholder="{{ __('Search destination, type, status, or approver...') }}" class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <select x-model="sort" @change="applyFilters()" class="block h-9 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-44">
                            <option value="latest">{{ __('Latest Filed') }}</option>
                            <option value="oldest">{{ __('Oldest Filed') }}</option>
                        </select>
                        <button type="button" @click="reset()" class="inline-flex h-9 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-xs font-bold uppercase tracking-wider text-gray-700 transition hover:bg-gray-50">
                            {{ __('Reset') }}
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Destination') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Type') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Date Filed') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Status') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="myTravelOrdersTableBody" class="divide-y divide-gray-100 bg-white">
                            @forelse($travelOrders as $order)
                                @php
                                    $searchText = Str::lower($order->places_of_travel . ' ' . $order->purpose . ' ' . $order->travel_type_label . ' ' . $order->status . ' ' . $order->travel_date_start->format('M d, Y'));
                                    $statusClass = match($order->status) {
                                        'approved' => 'bg-[#00c950] text-white',
                                        'rejected' => 'bg-red-500 text-white',
                                        'pending' => 'border border-orange-100 bg-orange-50 text-orange-700',
                                        default => 'bg-gray-400 text-white',
                                    };
                                    $stages = [
                                        ['label' => 'Chief', 'status' => $order->chief_status],
                                        ['label' => 'HR', 'status' => $order->hrstaff_status],
                                        ['label' => 'Director', 'status' => $order->rd_status],
                                    ];
                                    $travelTrackerPayload = [
                                        'id' => (string) $order->id,
                                        'title' => $order->places_of_travel,
                                        'stages' => collect($stages)->map(fn ($stage) => [
                                            'label' => $stage['label'],
                                            'status' => $stage['status'] ?: 'pending',
                                        ])->values(),
                                    ];
                                @endphp
                                <tr
                                    class="transition-colors duration-150 hover:bg-gray-50"
                                    :class="selectedId === '{{ $order->id }}' ? 'bg-sky-50' : ''"
                                    data-approval-row="{{ $order->id }}"
                                    data-travel-row
                                    data-search="{{ $searchText }}"
                                    data-status="{{ $order->status }}"
                                    data-filed="{{ $order->created_at?->timestamp ?? 0 }}"
                                >
                                    <td class="px-5 py-3 text-sm font-bold text-gray-900 whitespace-nowrap">
                                        <button type="button" @click="selectRow('{{ $order->id }}'); $dispatch('travel-selected', @js($travelTrackerPayload))" class="block max-w-[220px] truncate text-left font-bold text-blue-900 underline-offset-4 transition hover:text-blue-700 hover:underline" title="{{ __('Show approval progress for ') }}{{ $order->places_of_travel }}">
                                            {{ $order->places_of_travel }}
                                        </button>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $order->travel_type_label }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $order->created_at?->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-sm whitespace-nowrap">
                                        <span class="px-4 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm {{ $statusClass }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('travel-orders.show', $order) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-700 transition-colors hover:bg-indigo-50 hover:text-indigo-900" title="{{ __('View details') }}" aria-label="{{ __('View details') }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500">
                                        {{ __('No travel orders found.') }}
                                    </td>
                                </tr>
                            @endforelse
                            @if($travelOrders->isNotEmpty())
                                <tr x-show="noResults" style="display: none;">
                                    <td colspan="5" class="py-8 text-center text-gray-500">
                                        {{ __('No travel orders match your search or filter.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function myTravelOrdersTable(initialSelectedId = '') {
            return {
                selectedId: initialSelectedId ? String(initialSelectedId) : '',
                search: '',
                sort: 'latest',
                rows: [],
                noResults: false,
                init() {
                    this.rows = Array.from(document.querySelectorAll('[data-travel-row]'));
                    this.applyFilters();
                },
                selectRow(id) {
                    this.selectedId = String(id);
                },
                applyFilters() {
                    const search = this.search.trim().toLowerCase();
                    const tbody = document.getElementById('myTravelOrdersTableBody');
                    let visibleCount = 0;

                    this.rows.sort((a, b) => this.compareRows(a, b)).forEach((row) => {
                        const visible = !search || row.dataset.search.includes(search);

                        row.classList.toggle('hidden', !visible);
                        if (visible) visibleCount++;
                        tbody.appendChild(row);
                    });

                    this.noResults = this.rows.length > 0 && visibleCount === 0;
                },
                compareRows(a, b) {
                    const aFiled = Number(a.dataset.filed || 0);
                    const bFiled = Number(b.dataset.filed || 0);

                    if (this.sort === 'oldest') return aFiled - bFiled;
                    return bFiled - aFiled;
                },
                reset() {
                    this.search = '';
                    this.sort = 'latest';
                    this.applyFilters();
                },
            };
        }
    </script>
</x-app-layout>
