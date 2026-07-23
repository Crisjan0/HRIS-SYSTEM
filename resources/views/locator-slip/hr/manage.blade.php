<x-app-layout>
    <div class="p-4 sm:p-5 lg:p-6">
       

            <div class="p-4 md:p-5" x-data="manageLocatorSlips('{{ $tab }}')" x-init="init()">
                <div class="border-b border-gray-200 mb-4">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="tab = 'pending'"
                                :class="tab === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-3 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                            Pending Locator Slips
                        </button>
                        <button @click="tab = 'all'"
                                :class="tab === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-3 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                            All Locator Slips
                        </button>
                    </nav>
                </div>

                <div class="mb-4 flex min-w-0 flex-col gap-2 rounded-xl border border-gray-100 bg-gray-50/70 p-2 sm:flex-row sm:items-center">
                    <div class="relative min-w-0 sm:flex-1">
                        <label for="locatorSlipSearch" class="sr-only">{{ __('Search locator slip') }}</label>
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M21 21l-4.35-4.35M10.75 18.5a7.75 7.75 0 100-15.5 7.75 7.75 0 000 15.5z" />
                            </svg>
                        </span>
                        <input id="locatorSlipSearch" type="search" x-model="search" @input.debounce.200ms="applyFilters()" placeholder="{{ __('Search employee, destination, or purpose...') }}" class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <div class="sm:w-44 sm:shrink-0">
                        <label for="locatorSlipType" class="sr-only">{{ __('Locator Type') }}</label>
                        <select id="locatorSlipType" x-model="type" @change="applyFilters()" class="block h-9 w-full rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach($locatorTypes as $locatorType)
                                <option value="{{ Str::lower($locatorType) }}">{{ $locatorType }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:w-52 sm:shrink-0">
                        <label for="locatorSlipSort" class="sr-only">{{ __('Sort') }}</label>
                        <select id="locatorSlipSort" x-model="sort" @change="applyFilters()" class="block h-9 w-full rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="latest">{{ __('Latest Filed') }}</option>
                            <option value="oldest">{{ __('Oldest Filed') }}</option>
                            <option value="employee_asc">{{ __('Name A-Z') }}</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 sm:shrink-0">
                        <button type="button" @click="reset()" class="inline-flex h-9 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-xs font-bold uppercase tracking-wider text-gray-700 transition hover:bg-gray-50">
                            {{ __('Reset') }}
                        </button>
                    </div>
                </div>

                <div x-show="tab === 'pending'" x-cloak>
                    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-[860px] w-full table-fixed divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="w-[25%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500 whitespace-nowrap">Employee</th>
                                    <th scope="col" class="w-[15%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500 whitespace-nowrap">Date Covered</th>
                                    <th scope="col" class="w-[40%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500 whitespace-nowrap">Purpose</th>
                                    <th scope="col" class="w-[12%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500 whitespace-nowrap">Status</th>
                                    <th scope="col" class="w-[8%] px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500 whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pendingLocatorSlipRows" class="divide-y divide-gray-100 bg-white">
                                @forelse($pendingLocatorSlips as $slip)
                                    @php
                                        $displayStatus = $slip->status === 'approved by chief' ? 'approved' : strtolower($slip->status);
                                        $employeeName = trim(($slip->employee->firstname ?? '') . ' ' . ($slip->employee->lastname ?? ''));
                                        $employeePosition = $slip->employee?->position ?: __('No position');
                                        $displayType = ($slip->type ?? '') === 'Personal' ? 'Pass Slip' : ($slip->type ?? '');
                                        $searchText = Str::lower($employeeName . ' ' . ($slip->employee->position ?? '') . ' ' . ($slip->employee->division ?? '') . ' ' . ($slip->destination ?? '') . ' ' . $slip->purpose . ' ' . $displayType . ' ' . $displayStatus);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 group cursor-pointer" onclick="window.location='{{ route('hr.locator-slips.show', $slip->id) }}'"
                                        data-manage-locator-row="pending"
                                        data-search="{{ $searchText }}"
                                        data-type="{{ Str::lower($displayType) }}"
                                        data-employee="{{ Str::lower($employeeName) }}"
                                        data-filed="{{ $slip->created_at?->timestamp ?? 0 }}">
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <div class="truncate text-sm font-bold text-gray-900" title="{{ $employeeName }}">{{ $slip->employee->firstname }} {{ $slip->employee->lastname }}</div>
                                            <div class="truncate text-xs text-gray-400" title="{{ $employeePosition }}">{{ $employeePosition }}</div>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700">{{ $slip->date_covered }}</td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 uppercase"><div class="truncate" title="{{ $slip->purpose }}">{{ $slip->purpose }}</div></td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm
                                                @if($slip->status == 'approved') bg-[#00c950] text-white @endif
                                                @if($slip->status == 'approved by chief') bg-[#00c950] text-white @endif
                                                @if($slip->status == 'rejected') bg-red-500 text-white @endif
                                                @if(Str::contains($slip->status, 'pending')) border border-orange-100 bg-orange-50 text-orange-700 @endif
                                            ">
                                                {{ $slip->status === 'approved by chief' ? 'Approved' : ucfirst($slip->status) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('hr.locator-slips.show', $slip->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-700 transition-colors hover:bg-indigo-50 hover:text-indigo-900" title="View" aria-label="View">
                                                    <i class="fa-solid fa-eye"></i>
                                                    <span class="sr-only">View</span>
                                                </a>
                                                @if(strtolower(Auth::user()->role) === 'chief')
                                                    <form action="{{ route('locator-slips.approve', $slip->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-green-600 hover:bg-green-50 hover:text-green-900 transition-colors" title="Approve" aria-label="Approve">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span class="sr-only">Approve</span>
                                                        </button>
                                                    </form>
                                                @endif
                                                {{-- @if(in_array(strtolower(Auth::user()->role), ['chief', 'admin', 'hrstaff']))
                                                    <form action="{{ route('locator-slips.reject', $slip->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-600 hover:bg-red-50 hover:text-red-900 transition-colors" title="Reject" aria-label="Reject">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            <span class="sr-only">Reject</span>
                                                        </button>
                                                    </form>
                                                @endif --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 whitespace-nowrap text-sm text-gray-500 text-center">No pending locator slips found.</td>
                                    </tr>
                                @endforelse
                                @if($pendingLocatorSlips->isNotEmpty())
                                    <tr x-show="pendingNoResults" style="display: none;">
                                        <td colspan="5" class="px-4 py-8 whitespace-nowrap text-sm text-gray-500 text-center">No pending locator slips match your search or filter.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>

                <div x-show="tab === 'all'" x-cloak>
                    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-[860px] w-full table-fixed divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="w-[25%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500 whitespace-nowrap">Employee</th>
                                    <th scope="col" class="w-[15%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500 whitespace-nowrap">Date Covered</th>
                                    <th scope="col" class="w-[40%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500 whitespace-nowrap">Purpose</th>
                                    <th scope="col" class="w-[12%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500 whitespace-nowrap">Status</th>
                                    <th scope="col" class="w-[8%] px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500 whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="allLocatorSlipRows" class="divide-y divide-gray-100 bg-white">
                                @forelse($allLocatorSlips as $slip)
                                    @php
                                        $displayStatus = $slip->status === 'approved by chief' ? 'approved' : strtolower($slip->status);
                                        $employeeName = trim(($slip->employee->firstname ?? '') . ' ' . ($slip->employee->lastname ?? ''));
                                        $employeePosition = $slip->employee?->position ?: __('No position');
                                        $displayType = ($slip->type ?? '') === 'Personal' ? 'Pass Slip' : ($slip->type ?? '');
                                        $searchText = Str::lower($employeeName . ' ' . ($slip->employee->position ?? '') . ' ' . ($slip->employee->division ?? '') . ' ' . ($slip->destination ?? '') . ' ' . $slip->purpose . ' ' . $displayType . ' ' . $displayStatus);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 group cursor-pointer" onclick="window.location='{{ route('hr.locator-slips.show', $slip->id) }}'"
                                        data-manage-locator-row="all"
                                        data-search="{{ $searchText }}"
                                        data-type="{{ Str::lower($displayType) }}"
                                        data-employee="{{ Str::lower($employeeName) }}"
                                        data-filed="{{ $slip->created_at?->timestamp ?? 0 }}">
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <div class="truncate text-sm font-bold text-gray-900" title="{{ $employeeName }}">{{ $slip->employee->firstname }} {{ $slip->employee->lastname }}</div>
                                            <div class="truncate text-xs text-gray-400" title="{{ $employeePosition }}">{{ $employeePosition }}</div>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700">{{ $slip->date_covered }}</td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 uppercase"><div class="truncate" title="{{ $slip->purpose }}">{{ $slip->purpose }}</div></td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm
                                                @if($slip->status == 'approved') bg-[#00c950] text-white @endif
                                                @if($slip->status == 'approved by chief') bg-[#00c950] text-white @endif
                                                @if($slip->status == 'rejected') bg-red-500 text-white @endif
                                                @if(Str::contains($slip->status, 'pending')) border border-orange-100 bg-orange-50 text-orange-700 @endif
                                            ">
                                                {{ $slip->status === 'approved by chief' ? 'Approved' : ucfirst($slip->status) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                                            <a href="{{ route('hr.locator-slips.show', $slip->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-700 transition-colors hover:bg-indigo-50 hover:text-indigo-900" title="View" aria-label="View">
                                                <i class="fa-solid fa-eye"></i>
                                                <span class="sr-only">View</span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 whitespace-nowrap text-sm text-gray-500 text-center">No locator slips found.</td>
                                    </tr>
                                @endforelse
                                @if($allLocatorSlips->isNotEmpty())
                                    <tr x-show="allNoResults" style="display: none;">
                                        <td colspan="5" class="px-4 py-8 whitespace-nowrap text-sm text-gray-500 text-center">No locator slips match your search or filter.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function manageLocatorSlips(initialTab) {
            return {
                tab: initialTab || 'pending',
                search: '',
                type: '',
                sort: 'latest',
                pendingRows: [],
                allRows: [],
                pendingNoResults: false,
                allNoResults: false,
                init() {
                    this.pendingRows = Array.from(document.querySelectorAll('[data-manage-locator-row="pending"]'));
                    this.allRows = Array.from(document.querySelectorAll('[data-manage-locator-row="all"]'));
                    this.applyFilters();
                },
                applyFilters() {
                    this.pendingNoResults = this.filterRows(this.pendingRows, document.getElementById('pendingLocatorSlipRows')) === 0 && this.pendingRows.length > 0;
                    this.allNoResults = this.filterRows(this.allRows, document.getElementById('allLocatorSlipRows')) === 0 && this.allRows.length > 0;
                },
                filterRows(rows, tbody) {
                    const search = this.search.trim().toLowerCase();
                    let visibleCount = 0;

                    rows.sort((a, b) => this.compareRows(a, b)).forEach((row) => {
                        const visible = (!search || row.dataset.search.includes(search))
                            && (!this.type || row.dataset.type === this.type);

                        row.classList.toggle('hidden', !visible);
                        if (visible) visibleCount++;
                        tbody.appendChild(row);
                    });

                    return visibleCount;
                },
                compareRows(a, b) {
                    const aFiled = Number(a.dataset.filed || 0);
                    const bFiled = Number(b.dataset.filed || 0);

                    if (this.sort === 'oldest') return aFiled - bFiled;
                    if (this.sort === 'employee_asc') return (a.dataset.employee || '').localeCompare(b.dataset.employee || '');
                    return bFiled - aFiled;
                },
                reset() {
                    this.search = '';
                    this.type = '';
                    this.sort = 'latest';
                    this.applyFilters();
                },
            };
        }
    </script>
</x-app-layout>
