<x-app-layout>
    <x-slot name="title">{{ __('My Leave Requests') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @php
                $trackedLeave = $leaves->firstWhere('status', 'pending') ?? $leaves->first();
                $trackedStages = $trackedLeave ? [
                    ['label' => 'Chief', 'status' => $trackedLeave->chief_status],
                    ['label' => 'HR', 'status' => $trackedLeave->hrstaff_status],
                    ['label' => 'Director', 'status' => $trackedLeave->rd_status],
                ] : [];
                $trackedType = $trackedLeave
                    ? Str::of($trackedLeave->leaveType?->name ?? 'Leave')->replaceMatches('/\s+Leave\b/i', '')->trim()
                    : null;
                $trackedPayload = $trackedLeave ? [
                    'id' => (string) $trackedLeave->id,
                    'type' => (string) $trackedType,
                    'stages' => collect($trackedStages)->map(fn ($stage) => [
                        'label' => $stage['label'],
                        'status' => $stage['status'] ?: 'pending',
                    ])->values(),
                ] : null;
            @endphp

            <!-- Header Actions -->
            <div class="mb-6">
                <div class="mb-4 flex justify-end">
                    <a href="{{ route('leaves.create') }}" class="bg-blue-900 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-200 hover:-translate-y-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        File New Leave
                    </a>
                </div>

                <x-approval-tracker :payload="$trackedPayload" event="leave-selected" empty="No leave approval process to track yet." />
            </div>

            <div x-data="{ tab: 'applications' }">
                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="tab = 'applications'"
                                :class="tab === 'applications' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                            My Leave Applications
                        </button>
                        <button @click="tab = 'credits'"
                                :class="tab === 'credits' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                            My Leave Credits
                        </button>
                    </nav>
                </div>

                <!-- Credits Summary Tab -->
                <div x-show="tab === 'credits'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        @foreach($credits as $credit)
                            <div
                                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 ring-1 ring-black/5 transform hover:scale-105 transition-all duration-300">
                                <div class="text-[10px] font-black uppercase text-blue-900 tracking-widest mb-1">
                                    {{ $credit->leaveType->name }}</div>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-3xl font-black text-gray-900">{{ number_format($credit->balance, 1) }}</span>
                                    <span class="text-xs font-bold text-gray-400 capitalize">{{ __('Days Left') }}</span>
                                </div>
                                <div class="mt-4 w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                    @php
                                        $total = $credit->leaveType->days_per_year ?? 15;
                                        $percentage = $total > 0 ? ($credit->balance / $total) * 100 : 0;
                                        $color = $percentage > 50 ? 'bg-blue-900' : ($percentage > 20 ? 'bg-yellow-500' : 'bg-red-500');
                                    @endphp
                                    <div class="{{ $color }} h-full transition-all duration-1000" style="width: {{ $percentage }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Leave Applications Tab -->
                <div x-show="tab === 'applications'" x-data="myLeaveApplicationTable('{{ $trackedLeave?->id }}')" x-init="init()" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="mb-4 flex min-w-0 flex-col gap-2 rounded-xl border border-gray-100 bg-gray-50/70 p-2 sm:flex-row sm:items-center">
                        <div class="relative min-w-0 sm:flex-1">
                            <label for="my_leave_search" class="sr-only">{{ __('Search leave application') }}</label>
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M21 21l-4.35-4.35M10.75 18.5a7.75 7.75 0 100-15.5 7.75 7.75 0 000 15.5z" />
                                </svg>
                            </span>
                            <input id="my_leave_search" type="search" x-model="search" @input.debounce.200ms="applyFilters()" placeholder="{{ __('Search leave type, status, or date...') }}" class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <div class="sm:w-48 sm:shrink-0">
                            <label for="my_leave_sort" class="sr-only">{{ __('Sort') }}</label>
                            <select id="my_leave_sort" x-model="sort" @change="applyFilters()" class="block h-9 w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="date_filed_desc">{{ __('Newest Filed') }}</option>
                                <option value="date_filed_asc">{{ __('Oldest Filed') }}</option>
                            </select>
                        </div>
                        <button type="button" @click="reset()" class="inline-flex h-9 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-xs font-bold uppercase tracking-wider text-gray-700 transition hover:bg-gray-50 sm:shrink-0">
                            {{ __('Reset') }}
                        </button>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-[720px] w-full table-fixed divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="w-[34%] px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">Leave Type</th>
                                        <th scope="col" class="w-[14%] px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">Days</th>
                                        <th scope="col" class="w-[20%] px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">Date Filed</th>
                                        <th scope="col" class="w-[20%] px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">Status</th>
                                        <th scope="col" class="w-[12%] px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="myLeaveApplicationTableBody" class="divide-y divide-gray-100 bg-white">
                                    @forelse($leaves as $leaf)
                                        @php
                                            $stages = [
                                                ['label' => 'Chief', 'status' => $leaf->chief_status],
                                                ['label' => 'HR', 'status' => $leaf->hrstaff_status],
                                                ['label' => 'Director', 'status' => $leaf->rd_status],
                                            ];
                                            $approvedCount = collect($stages)->where('status', 'approved')->count();
                                            $hasRejected = collect($stages)->contains(fn($stage) => $stage['status'] === 'rejected');
                                            $displayStatus = $leaf->status === 'cancelled' ? 'Cancelled' : ($hasRejected ? 'Rejected' : ($approvedCount === 3 ? 'Approved' : 'Pending'));
                                            $filterStatus = $leaf->status === 'cancelled' ? 'cancelled' : ($hasRejected ? 'rejected' : ($approvedCount === 3 ? 'approved' : 'pending'));
                                            $statusClass = $hasRejected
                                                ? 'border-red-100 bg-red-50 text-red-700'
                                                : ($approvedCount === 3 ? 'border-green-100 bg-green-50 text-green-700' : 'border-orange-100 bg-orange-50 text-orange-700');
                                            $leaveTypeName = Str::of($leaf->leaveType?->name ?? '')->replaceMatches('/\s+Leave\b/i', '')->trim();
                                            $searchText = Str::lower($leaveTypeName . ' ' . $displayStatus . ' ' . \Carbon\Carbon::parse($leaf->date_filed)->format('M d, Y'));
                                            $leaveDaysLabel = $leaf->duration . ' ' . Str::plural('day', $leaf->duration);
                                            $leaveTrackerPayload = [
                                                'id' => (string) $leaf->id,
                                                'type' => (string) $leaveTypeName,
                                                'stages' => collect($stages)->map(fn ($stage) => [
                                                    'label' => $stage['label'],
                                                    'status' => $stage['status'] ?: 'pending',
                                                ])->values(),
                                            ];
                                        @endphp
                                        <tr
                                            class="transition-colors hover:bg-gray-50/70"
                                            :class="selectedId === '{{ $leaf->id }}' ? 'bg-sky-50' : ''"
                                            data-approval-row="{{ $leaf->id }}"
                                            data-leave-row
                                            data-search="{{ $searchText }}"
                                            data-leave-type="{{ Str::lower($leaveTypeName) }}"
                                            data-status="{{ $filterStatus }}"
                                            data-date-filed="{{ \Carbon\Carbon::parse($leaf->date_filed)->timestamp }}"
                                            data-leave-start="{{ \Carbon\Carbon::parse($leaf->start_date)->timestamp }}"
                                        >
                                            <td class="px-5 py-3 align-middle">
                                                <button type="button" @click="selectRow('{{ $leaf->id }}'); $dispatch('leave-selected', @js($leaveTrackerPayload))" class="w-full min-w-0 overflow-hidden text-left text-sm font-bold text-blue-900 underline-offset-4 transition hover:text-blue-700 hover:underline" title="{{ __('Show approval process for ') }}{{ $leaveTypeName }}">
                                                    <span class="block truncate">{{ $leaveTypeName }}</span>
                                                </button>
                                            </td>
                                            <td class="px-5 py-3 align-middle">
                                                <div class="whitespace-nowrap text-sm font-bold text-gray-700">
                                                    {{ $leaveDaysLabel }}
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 align-middle">
                                                <div class="whitespace-nowrap text-sm font-medium text-gray-700">
                                                    {{ \Carbon\Carbon::parse($leaf->date_filed)->format('M d, Y') }}
                                                </div>
                                            </td>
                                            {{-- <td class="hidden">
                                                <div class="hidden">
                                                    @foreach($stages as $stage)
                                                        @php
                                                            $stageClass = match($stage['status']) {
                                                                'approved' => 'bg-green-500',
                                                                'rejected' => 'bg-red-500',
                                                                default => 'bg-gray-300',
                                                            };
                                                        @endphp
                                                        <div class="flex items-center gap-2" title="{{ $stage['label'] }}: {{ $stage['status'] ? ucfirst($stage['status']) : 'Pending' }}">
                                                            <span class="h-2.5 w-2.5 rounded-full {{ $stageClass }}"></span>
                                                            <span class="text-xs font-medium text-gray-500">{{ $stage['label'] }}</span>
                                                        </div>
                                                        @if(! $loop->last)
                                                            <span class="text-gray-300">→</span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td> --}}
                                            <td class="px-5 py-3 align-middle">
                                                <span class="inline-flex rounded-full border px-2.5 py-1 text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">
                                                    {{ $displayStatus }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-3 text-right align-middle">
                                                <a href="{{ route('leaves.show', $leaf) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-700 transition-colors hover:bg-blue-50 hover:text-blue-900" title="{{ __('View details') }}" aria-label="{{ __('View details') }}">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <div class="text-gray-400 mb-2">
                                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <p class="text-lg font-medium text-gray-400 italic">
                                                    {{ __('You haven\'t filed any leave requests yet.') }}
                                                </p>
                                                <a href="{{ route('leaves.create') }}"
                                                    class="mt-4 inline-flex items-center text-blue-600 hover:underline font-bold text-sm">
                                                    {{ __('File your first leave now') }}
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                    @if($leaves->isNotEmpty())
                                        <tr x-show="noResults" style="display: none;">
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <p class="text-gray-500 italic font-medium">
                                                    {{ __('No leave applications match your search or filter.') }}
                                                </p>
                                            </td>
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
        function myLeaveApplicationTable(initialSelectedId = '') {
            return {
                selectedId: initialSelectedId ? String(initialSelectedId) : '',
                search: '',
                leaveType: '',
                status: '',
                sort: 'date_filed_desc',
                rows: [],
                noResults: false,
                init() {
                    this.rows = Array.from(document.querySelectorAll('[data-leave-row]'));
                    this.applyFilters();
                },
                selectRow(id) {
                    this.selectedId = String(id);
                },
                applyFilters() {
                    const search = this.search.trim().toLowerCase();
                    const tbody = document.getElementById('myLeaveApplicationTableBody');
                    let visibleCount = 0;

                    this.rows
                        .sort((a, b) => this.compareRows(a, b))
                        .forEach((row) => {
                            const matchesSearch = !search || row.dataset.search.includes(search);
                            const matchesType = !this.leaveType || row.dataset.leaveType === this.leaveType;
                            const matchesStatus = !this.status || row.dataset.status === this.status;
                            const isVisible = matchesSearch && matchesType && matchesStatus;

                            row.classList.toggle('hidden', !isVisible);
                            if (isVisible) visibleCount++;
                            tbody.appendChild(row);
                        });

                    this.noResults = this.rows.length > 0 && visibleCount === 0;
                },
                compareRows(a, b) {
                    const aFiled = Number(a.dataset.dateFiled || 0);
                    const bFiled = Number(b.dataset.dateFiled || 0);
                    const aStart = Number(a.dataset.leaveStart || 0);
                    const bStart = Number(b.dataset.leaveStart || 0);

                    if (this.sort === 'date_filed_asc') return aFiled - bFiled;
                    return bFiled - aFiled;
                },
                reset() {
                    this.search = '';
                    this.leaveType = '';
                    this.status = '';
                    this.sort = 'date_filed_desc';
                    this.applyFilters();
                },
            };
        }
    </script>
</x-app-layout>
