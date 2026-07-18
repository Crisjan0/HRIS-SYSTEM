<x-app-layout>
    <x-slot name="title">{{ __('My Compensatory Time-Off') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $trackedCto = $ctoRequests->firstWhere('status', 'pending') ?? $ctoRequests->first();
                $trackedCtoPayload = $trackedCto ? [
                    'id' => (string) $trackedCto->id,
                    'title' => $trackedCto->type_label,
                    'stages' => collect([
                        ['label' => 'Chief', 'status' => $trackedCto->chief_status],
                        ['label' => 'HR', 'status' => $trackedCto->hrstaff_status],
                        ['label' => 'Director', 'status' => $trackedCto->rd_status],
                    ])->map(fn ($stage) => [
                        'label' => $stage['label'],
                        'status' => $stage['status'] ?: 'pending',
                    ])->values(),
                ] : null;
            @endphp

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-black text-gray-900">{{ __('My Compensatory Time-Off') }}</h1>
                <a href="{{ route('my-cto.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-200 hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('File CTO Request') }}
                </a>
            </div>

            <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-xl shadow-indigo-200 md:col-span-1">
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-200">Available Balance</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-4xl font-black">{{ number_format($employee->cto_balance, 1) }}</span>
                        <span class="text-sm font-bold text-indigo-200 uppercase">Hours</span>
                    </div>
                    <p class="mt-2 text-xs text-indigo-200">Compensatory time-off credits available to use</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm md:col-span-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Quick Guide</span>
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-start gap-3">
                            <span class="shrink-0 w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-black">+</span>
                            <div>
                                <p class="font-bold text-gray-900">Earn CTO</p>
                                <p class="text-xs text-gray-500 mt-0.5">File overtime or extra work rendered to earn credits.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="shrink-0 w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">-</span>
                            <div>
                                <p class="font-bold text-gray-900">Use CTO</p>
                                <p class="text-xs text-gray-500 mt-0.5">Apply to use earned credits as time off.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-approval-tracker :payload="$trackedCtoPayload" event="cto-selected" empty="No CTO request to track yet." />

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-gray-100 overflow-hidden" x-data="myCtoRequestsTable()" x-init="init()">
                <div class="border-b border-gray-100 bg-gray-50/70 p-2">
                    <div class="flex min-w-0 flex-col gap-2 sm:flex-row sm:items-center">
                        <div class="relative min-w-0 sm:flex-1">
                            <label for="my_cto_search" class="sr-only">{{ __('Search CTO request') }}</label>
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M21 21l-4.35-4.35M10.75 18.5a7.75 7.75 0 100-15.5 7.75 7.75 0 000 15.5z" />
                                </svg>
                            </span>
                            <input id="my_cto_search" type="search" x-model="search" @input.debounce.200ms="applyFilters()" placeholder="{{ __('Search type, status, or approver...') }}" class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Type') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Date Filed') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Status') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="myCtoRequestsTableBody" class="divide-y divide-gray-100 bg-white">
                            @forelse($ctoRequests as $request)
                                @php
                                    $searchText = Str::lower($request->type_label . ' ' . $request->purpose . ' ' . $request->status . ' ' . $request->date_start->format('M d, Y'));
                                    $statusClass = match($request->status) {
                                        'approved' => 'bg-[#00c950] text-white',
                                        'rejected' => 'bg-red-500 text-white',
                                        'pending' => 'border border-orange-100 bg-orange-50 text-orange-700',
                                        default => 'bg-gray-400 text-white',
                                    };
                                    $stages = [
                                        ['label' => 'Chief', 'status' => $request->chief_status],
                                        ['label' => 'HR', 'status' => $request->hrstaff_status],
                                        ['label' => 'Director', 'status' => $request->rd_status],
                                    ];
                                    $ctoTrackerPayload = [
                                        'id' => (string) $request->id,
                                        'title' => $request->type_label,
                                        'stages' => collect($stages)->map(fn ($stage) => [
                                            'label' => $stage['label'],
                                            'status' => $stage['status'] ?: 'pending',
                                        ])->values(),
                                    ];
                                @endphp
                                <tr
                                    class="transition-colors duration-150 hover:bg-gray-50"
                                    data-approval-row="{{ $request->id }}"
                                    data-cto-row
                                    data-search="{{ $searchText }}"
                                    data-filed="{{ $request->created_at?->timestamp ?? 0 }}"
                                >
                                    <td class="px-5 py-3 text-sm font-bold text-gray-900 whitespace-nowrap">
                                        <button type="button" @click="$dispatch('cto-selected', @js($ctoTrackerPayload))" class="text-left font-bold text-blue-900 underline-offset-4 transition hover:text-blue-700 hover:underline" title="{{ __('Show approval progress for ') }}{{ $request->type_label }}">
                                            {{ $request->type_label }}
                                        </button>
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">{{ $request->created_at?->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-sm whitespace-nowrap">
                                        <span class="px-4 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm {{ $statusClass }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('my-cto.show', $request) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-700 transition-colors hover:bg-indigo-50 hover:text-indigo-900" title="{{ __('View details') }}" aria-label="{{ __('View details') }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500">
                                        {{ __('No compensatory time-off records found.') }}
                                    </td>
                                </tr>
                            @endforelse
                            @if($ctoRequests->isNotEmpty())
                                <tr x-show="noResults" style="display: none;">
                                    <td colspan="4" class="py-8 text-center text-gray-500">
                                        {{ __('No CTO requests match your search or filter.') }}
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
        function myCtoRequestsTable() {
            return {
                search: '',
                sort: 'latest',
                rows: [],
                noResults: false,
                init() {
                    this.rows = Array.from(document.querySelectorAll('[data-cto-row]'));
                    this.applyFilters();
                },
                applyFilters() {
                    const search = this.search.trim().toLowerCase();
                    const tbody = document.getElementById('myCtoRequestsTableBody');
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
