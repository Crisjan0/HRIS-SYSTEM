<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-100 overflow-hidden" x-data="myLocatorSlipTable()" x-init="init()">
            <div class="p-6 flex justify-between items-center border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800">My Locator Slips</h2>
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
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M21 21l-4.35-4.35M10.75 18.5a7.75 7.75 0 100-15.5 7.75 7.75 0 000 15.5z" />
                            </svg>
                        </span>
                        <input id="my_locator_search" type="search" x-model="search" @input.debounce.200ms="applyFilters()" placeholder="{{ __('Search date, type, status, destination, or purpose...') }}" class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
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
                                $searchText = Str::lower(($slip->destination ?? '') . ' ' . $slip->purpose . ' ' . ($slip->type ?? '') . ' ' . $displayStatus . ' ' . \Carbon\Carbon::parse($slip->date_covered)->format('M d, Y'));
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
                                data-type="{{ Str::lower($slip->type ?? '') }}"
                                data-status="{{ $displayStatus }}"
                                data-filed="{{ $slip->created_at?->timestamp ?? 0 }}"
                                data-covered="{{ \Carbon\Carbon::parse($slip->date_covered)->timestamp }}"
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
                                    {{ $slip->type ?? 'N/A' }}
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
                sort: 'latest',
                rows: [],
                noResults: false,
                init() {
                    this.rows = Array.from(document.querySelectorAll('[data-locator-row]'));
                    this.applyFilters();
                },
                applyFilters() {
                    const search = this.search.trim().toLowerCase();
                    const tbody = document.getElementById('myLocatorSlipTableBody');
                    let visibleCount = 0;

                    this.rows.sort((a, b) => this.compareRows(a, b)).forEach((row) => {
                        const visible = (!search || row.dataset.search.includes(search))
                            && (!this.type || row.dataset.type === this.type)
                            && (!this.status || row.dataset.status === this.status);

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
                    this.type = '';
                    this.status = '';
                    this.sort = 'latest';
                    this.applyFilters();
                },
            };
        }
    </script>
</x-app-layout>
