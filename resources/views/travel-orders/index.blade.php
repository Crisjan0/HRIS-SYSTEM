<x-app-layout>
    <x-slot name="title">{{ __('My Travel Authorities') }}</x-slot>

    <div class="py-12">
        <div
            class="max-w-7xl mx-auto sm:px-6 lg:px-8"
            x-data="myTravelOrdersTable()"
            x-init="init()"
        >
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $currentYear = now()->year;
            @endphp

            {{-- Year filter outside the container --}}
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold text-gray-800">{{ __('Travel Authorities') }}</h1>
                    <p class="text-sm text-gray-500">{{ __('View your travel authorities.') }}</p>
                </div>

                <div class="flex items-center gap-2">
                    <label for="travel_year" class="text-sm font-semibold text-gray-700">
                        {{ __('Year') }}
                    </label>

                    <select
                        id="travel_year"
                        x-model="year"
                        @change="applyFilters()"
                        class="h-10 w-28 appearance-auto rounded-lg border-gray-300 bg-white pl-4 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @for ($yearOption = $currentYear; $yearOption >= $currentYear - 4; $yearOption--)
                            <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 flex justify-end items-center border-b border-gray-100">
                    
                    <a href="{{ route('travel-orders.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-900 px-4 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('New Travel Authority') }}
                    </a>
                </div>

                <div class="border-b border-gray-100 bg-gray-50/70 p-2">
                    <div class="flex min-w-0 flex-col gap-2 sm:flex-row sm:items-center">
                        <div class="relative min-w-0 sm:flex-1">
                            <label for="my_travel_search" class="sr-only">{{ __('Search travel authority') }}</label>
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M21 21l-4.35-4.35M10.75 18.5a7.75 7.75 0 100-15.5 7.75 7.75 0 000 15.5z" />
                                </svg>
                            </span>
                            <input id="my_travel_search" type="search" x-model="search" @input.debounce.200ms="applyFilters()" placeholder="{{ __('Search destination or approver...') }}" class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <select x-model="status" @change="applyFilters()" class="block h-9 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-40">
                            <option value="">{{ __('Statuses') }}</option>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="approved">{{ __('Approved') }}</option>
                            <option value="rejected">{{ __('Rejected') }}</option>
                        </select>

                        <select
    x-model="type"
    @change="applyFilters()"
    class="block h-9 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-44"
>
    <option value="">{{ __('Types') }}</option>
    <option value="local">{{ __('Local') }}</option>
    <option value="foreign">{{ __('Foreign') }}</option>
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
                                <th scope="col" class="w-[28%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Destination') }}</th>
                                <th scope="col" class="w-[16%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Type') }}</th>
                                <th scope="col" class="w-[20%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Approval') }}</th>
                                <th scope="col" class="w-[14%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Status') }}</th>
                                <th scope="col" class="w-[14%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Date Filed') }}</th>
                                <th scope="col" class="w-[8%] px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="myTravelOrdersTableBody" class="divide-y divide-gray-100 bg-white">
                            @forelse($travelOrders as $order)
                                @php
                                    $rdStatus = strtolower($order->rd_status ?: 'pending');
                                    $searchText = Str::lower(
                                        $order->places_of_travel . ' ' .
                                        $order->purpose . ' ' .
                                        $order->travel_type_label . ' ' .
                                        $order->status . ' ' .
                                        $rdStatus . ' Regional Director ' .
                                        $order->travel_date_start->format('M d, Y')
                                    );

                                    $approvalDotClass = match($rdStatus) {
                                        'approved' => 'bg-green-500',
                                        'rejected' => 'bg-red-500',
                                        default => 'bg-gray-300',
                                    };

                                    $approvalTextClass = match($rdStatus) {
                                        'approved' => 'text-green-700',
                                        'rejected' => 'text-red-700',
                                        default => 'text-gray-500',
                                    };

                                    $displayStatus = strtolower($order->status ?: 'pending');
                                    $statusClass = match($displayStatus) {
                                        'approved' => 'bg-[#00c950] text-white',
                                        'rejected' => 'bg-red-500 text-white',
                                        'pending' => 'border border-orange-100 bg-orange-50 text-orange-700',
                                        default => 'bg-gray-400 text-white',
                                    };
                                @endphp
                                <tr
                                    class="transition-colors duration-150 hover:bg-gray-50"
                                    data-travel-row
                                    data-search="{{ $searchText }}"
                                    data-status="{{ $displayStatus }}"
                                    data-type="{{ Str::lower($order->travel_type_label) }}"
                                    data-filed="{{ $order->created_at?->timestamp ?? 0 }}"
                                    data-year="{{ $order->created_at?->year ?? $currentYear }}"
                                >
                                    <td class="px-5 py-3 text-sm font-bold text-gray-900 whitespace-nowrap">
                                        <span class="block max-w-[240px] truncate" title="{{ $order->places_of_travel }}">
                                            {{ $order->places_of_travel }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $order->travel_type_label }}
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2.5 w-2.5 rounded-full {{ $approvalDotClass }}"></span>
                                            <div>
                                                <div class="text-xs font-medium text-gray-500">{{ __('Regional Director') }}</div>
                                               
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-sm whitespace-nowrap">
                                        <span class="inline-flex rounded-full px-4 py-1 text-xs font-semibold leading-5 shadow-sm {{ $statusClass }}">
                                            {{ ucfirst($displayStatus) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $order->created_at?->format('M d, Y') }}
                                    </td>
                                    <td class="px-5 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('travel-orders.show', $order) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-700 transition-colors hover:bg-indigo-50 hover:text-indigo-900" title="{{ __('View details') }}" aria-label="{{ __('View details') }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500">
                                        {{ __('No travel authorities found.') }}
                                    </td>
                                </tr>
                            @endforelse
                            @if($travelOrders->isNotEmpty())
                                <tr x-show="noResults" style="display: none;">
                                    <td colspan="6" class="py-8 text-center text-gray-500">
                                        {{ __('No travel authorities match your search or filter.') }}
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
        function myTravelOrdersTable() {
            return {
                search: '',
                status: '',
                type: '',
                year: '{{ now()->year }}',
                rows: [],
                noResults: false,
                init() {
                    this.rows = Array.from(document.querySelectorAll('[data-travel-row]'));
                    this.applyFilters();
                },
                applyFilters() {
                    const search = this.search.trim().toLowerCase();
                    const tbody = document.getElementById('myTravelOrdersTableBody');
                    let visibleCount = 0;

                    this.rows.forEach((row) => {
                        const visible =
                            (!search || row.dataset.search.includes(search))
                            && (!this.status || row.dataset.status === this.status)
                            && (!this.type || row.dataset.type === this.type)
                            && row.dataset.year === this.year;

                        row.classList.toggle('hidden', !visible);
                        if (visible) visibleCount++;
                        tbody.appendChild(row);
                    });

                    this.noResults = this.rows.length > 0 && visibleCount === 0;
                },
                reset() {
                    this.search = '';
                    this.status = '';
                    this.type = '';
                    this.year = '{{ now()->year }}';
                    this.applyFilters();
                },
            };
        }
    </script>
</x-app-layout>