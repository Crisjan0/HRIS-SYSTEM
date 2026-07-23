<x-app-layout>
    <x-slot name="title">{{ __('Manage Compensatory Time-Off') }}</x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

                <div class="p-4 md:p-5" x-data="manageCtoRequests('{{ $tab }}')" x-init="init()">
                    @php
                        $trackedCto = $pendingCtoRequests->first() ?? $allCtoRequests->first();
                        $trackedCtoPayload = $trackedCto ? [
                            'title' => trim(($trackedCto->employee?->firstname ?? '') . ' ' . ($trackedCto->employee?->lastname ?? '')),
                            'stages' => collect([
                                ['label' => 'HR', 'status' => $trackedCto->hrstaff_status],
                                ['label' => 'Chief', 'status' => $trackedCto->chief_status],
                                ['label' => 'Regional Director', 'status' => $trackedCto->rd_status],
                            ])->map(fn ($stage) => [
                                'label' => $stage['label'],
                                'status' => $stage['status'] ?: 'pending',
                            ])->values(),
                        ] : null;
                    @endphp

                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button @click="tab = 'pending'"
                                    :class="tab === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-3 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                                {{ __('Pending CTO Requests') }}
                            </button>
                            <button @click="tab = 'all'"
                                    :class="tab === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-3 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                                {{ __('All CTO Requests') }}
                            </button>
                        </nav>
                    </div>

                    <x-approval-tracker :payload="$trackedCtoPayload" event="cto-selected" empty="No CTO request to track yet." />

                    <div class="mb-4 flex min-w-0 flex-col gap-2 rounded-xl border border-gray-100 bg-gray-50/70 p-2 sm:flex-row sm:items-center">
                        <div class="relative min-w-0 sm:flex-1">
                            <label for="ctoSearch" class="sr-only">{{ __('Search CTO request') }}</label>
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M21 21l-4.35-4.35M10.75 18.5a7.75 7.75 0 100-15.5 7.75 7.75 0 000 15.5z" />
                                </svg>
                            </span>
                            <input id="ctoSearch" type="search" x-model="search" @input.debounce.200ms="applyFilters()" placeholder="{{ __('Search employee, type, status, or approver...') }}" class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <div class="sm:w-44 sm:shrink-0">
                            <label for="ctoType" class="sr-only">{{ __('CTO Type') }}</label>
                            <select id="ctoType" x-model="type" @change="applyFilters()" class="block h-9 w-full rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('All Types') }}</option>
                                <option value="earn">{{ __('Earn CTO') }}</option>
                                <option value="use">{{ __('Use CTO') }}</option>
                            </select>
                        </div>
                        <div class="sm:w-52 sm:shrink-0">
                            <label for="ctoSort" class="sr-only">{{ __('Sort') }}</label>
                            <select id="ctoSort" x-model="sort" @change="applyFilters()" class="block h-9 w-full rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                                <table class="w-full table-fixed divide-y divide-gray-100">
                                    <thead class="bg-gray-50/80">
                                        <tr>
                                            <th class="w-[40%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">{{ __('Employee') }}</th>
                                            <th class="w-[25%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">{{ __('Type') }}</th>
                                            <th class="w-[20%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">{{ __('Date Filed') }}</th>
                                            <th class="w-[15%] px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pendingCtoRows" class="divide-y divide-gray-100 bg-white">
                                        @include('my-cto._admin-rows', ['requests' => $pendingCtoRequests, 'emptyMessage' => __('No pending CTO requests.'), 'actionMode' => 'review'])
                                        @if($pendingCtoRequests->isNotEmpty())
                                            <tr x-show="pendingNoResults" style="display: none;">
                                                <td colspan="4" class="px-4 py-10 text-center text-sm font-medium italic text-gray-500">{{ __('No pending CTO requests match your search or filter.') }}</td>
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
                                <table class="w-full table-fixed divide-y divide-gray-100">
                                    <thead class="bg-gray-50/80">
                                        <tr>
                                            <th class="w-[40%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">{{ __('Employee') }}</th>
                                            <th class="w-[25%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">{{ __('Type') }}</th>
                                            <th class="w-[20%] px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">{{ __('Date Filed') }}</th>
                                            <th class="w-[15%] px-4 py-3 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="allCtoRows" class="divide-y divide-gray-100 bg-white">
                                        @include('my-cto._admin-rows', ['requests' => $allCtoRequests, 'emptyMessage' => __('No approved or rejected CTO requests yet.'), 'actionMode' => 'view'])
                                        @if($allCtoRequests->isNotEmpty())
                                            <tr x-show="allNoResults" style="display: none;">
                                                <td colspan="4" class="px-4 py-10 text-center text-sm font-medium italic text-gray-500">{{ __('No CTO requests match your search or filter.') }}</td>
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
    </div>
    <script>
        function manageCtoRequests(initialTab) {
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
                    this.pendingRows = Array.from(document.querySelectorAll('[data-manage-cto-row="pending"]'));
                    this.allRows = Array.from(document.querySelectorAll('[data-manage-cto-row="all"]'));
                    this.applyFilters();
                },
                applyFilters() {
                    this.pendingNoResults = this.filterRows(this.pendingRows, document.getElementById('pendingCtoRows')) === 0 && this.pendingRows.length > 0;
                    this.allNoResults = this.filterRows(this.allRows, document.getElementById('allCtoRows')) === 0 && this.allRows.length > 0;
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
