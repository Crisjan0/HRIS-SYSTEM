<x-app-layout>
    <div
    class="p-4 sm:p-6 lg:p-8"
    x-data="myLocatorSlipTable()"
    x-init="init()"
>
    {{-- Year filter outside the container --}}
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-gray-800">
                Locator Slips
            </h1>

            <p class="text-sm text-gray-500">
                View your locator slips.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <label
                for="locator_year"
                class="text-sm font-semibold text-gray-700"
            >
                Year
            </label>
    @php
    $currentYear = now()->year;
    @endphp

            <select
                id="locator_year"
                x-model="year"
                @change="applyFilters()"
               class="h-10 w-28 appearance-auto rounded-lg border-gray-300 bg-white pl-4 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                @for ($yearOption = $currentYear; $yearOption >= $currentYear - 4; $yearOption--)
            <option value="{{ $yearOption }}">
                {{ $yearOption }}
            </option>
        @endfor
    </select>
        </div>
    </div>

    {{-- Main locator slip container --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 flex justify-end items-center border-b border-gray-100">
                <a href="{{ route('locator-slips.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-900 px-4 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Locator Slip
                </a>
            </div>

            <div class="border-b border-gray-100 bg-gray-50/70 p-2">
                <div class="flex min-w-0 flex-col gap-2 sm:flex-row sm:items-center">
                    <div class="relative min-w-0 sm:flex-1">
                        <label for="my_locator_search" class="sr-only">{{ __('Search locator slip') }}</label>
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
        <svg
            class="h-4 w-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            aria-hidden="true"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
            />
        </svg>
    </span>
                        <input id="my_locator_search" type="search" x-model="search" @input.debounce.200ms="applyFilters()" placeholder="{{ __('Search date, type, status, destination, or purpose...') }}" class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <select x-model="status" @change="applyFilters()" class="block h-9 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-40">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="pending">{{ __('Pending') }}</option>
                        <option value="approved">{{ __('Approved') }}</option>
                        <option value="rejected">{{ __('Rejected') }}</option>
                    </select>

                    <select x-model="type" @change="applyFilters()" class="block h-9 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-40">
                        <option value="">{{ __('All Types') }}</option>
                        @foreach(
                            $locatorSlips
                                ->map(fn ($slip) => ($slip->type ?? '') === 'Personal' ? 'Pass Slip' : ($slip->type ?? ''))
                                ->filter()
                                ->unique()
                                ->sort()
                            as $typeOption
                        )
                            <option value="{{ Str::lower($typeOption) }}">{{ $typeOption }}</option>
                        @endforeach
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Date Covered</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Approval</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="myLocatorSlipTableBody" class="divide-y divide-gray-100 bg-white">
                        @forelse($locatorSlips as $slip)
                            @php
                                $displayStatus = strtolower($slip->status) === 'approved by chief' ? 'approved' : strtolower($slip->status);
                                $approvalStatus = $displayStatus === 'approved' ? 'approved' : ($displayStatus === 'rejected' ? 'rejected' : 'pending');
                                $displayType = ($slip->type ?? '') === 'Personal' ? 'Pass Slip' : ($slip->type ?? '');
                                $searchText = Str::lower(($slip->destination ?? '') . ' ' . $slip->purpose . ' ' . $displayType . ' ' . $displayStatus . ' ' . \Carbon\Carbon::parse($slip->date_covered)->format('M d, Y'));
                                $statusClass = match($displayStatus) {
                                    'approved' => 'bg-[#00c950] text-white',
                                    'rejected' => 'bg-red-500 text-white',
                                    'pending' => 'border border-orange-100 bg-orange-50 text-orange-700',
                                    default => 'bg-gray-400 text-white',
                                };
                                $approvalDotClass = match($approvalStatus) {
                                    'approved' => 'bg-green-500',
                                    'rejected' => 'bg-red-500',
                                    default => 'bg-gray-300',
                                };
                            @endphp
                            <tr
                                class="hover:bg-gray-50 transition-colors duration-150"
                                data-locator-row
                                data-search="{{ $searchText }}"
                                data-type="{{ Str::lower($displayType) }}"
                                data-status="{{ $displayStatus }}"
                                data-year="{{ \Carbon\Carbon::parse($slip->date_covered)->year }}"
                            >
                                <td class="py-4 px-6 text-sm font-bold text-gray-900 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($slip->date_covered)->format('M d, Y') }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-700 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full {{ $approvalDotClass }}"></span>
                                        <span class="text-xs font-medium text-gray-500">Chief</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $displayType ?: 'N/A' }}
                                </td>
                                <td class="py-4 px-6 text-sm whitespace-nowrap">
                                    <span class="px-4 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm {{ $statusClass }}">
                                        {{ ucfirst($displayStatus) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <a href="{{ route('locator-slips.show', $slip) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-700 transition-colors hover:bg-indigo-50 hover:text-indigo-900" title="{{ __('View details') }}" aria-label="{{ __('View details') }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">
                                    No locator slips found.
                                </td>
                            </tr>
                        @endforelse
                        @if($locatorSlips->isNotEmpty())
                            <tr x-show="noResults" style="display: none;">
                                <td colspan="5" class="py-8 text-center text-gray-500">
                                    {{ __('No locator slips match your search or filter.') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function myLocatorSlipTable() {
            return {
                search: '',
                type: '',
                status: '',
                year: '{{ now()->year }}',
                rows: [],
                noResults: false,
                init() {
                    this.rows = Array.from(document.querySelectorAll('[data-locator-row]'));
                    this.applyFilters();
                },
                applyFilters() {
                    const search = this.search.trim().toLowerCase();
                    let visibleCount = 0;

                    this.rows.forEach((row) => {
                        const visible =
    (!search || row.dataset.search.includes(search))
    && (!this.type || row.dataset.type === this.type)
    && (!this.status || row.dataset.status === this.status)
    && (!this.year || row.dataset.year === this.year);

                        row.classList.toggle('hidden', !visible);
                        if (visible) visibleCount++;
                    });

                    this.noResults = this.rows.length > 0 && visibleCount === 0;
                },
                reset() {
                    this.search = '';
                    this.type = '';
                    this.status = '';
                    this.year = '{{ now()->year }}';
                    this.applyFilters();
                },
            };
        }
    </script>
</x-app-layout>