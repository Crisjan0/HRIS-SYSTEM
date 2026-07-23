<x-app-layout>
    <x-slot name="title">{{ __('My Leave Requests') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="myLeaveApplicationTable()" x-init="init()">
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
                    ['label' => 'HR', 'status' => $trackedLeave->hrstaff_status],
                    ['label' => 'Chief', 'status' => $trackedLeave->chief_status],
                    ['label' => 'Regional Director', 'status' => $trackedLeave->rd_status],
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

                $currentYear = now()->year;
                $leaveTypes = $leaves
                    ->map(fn ($leave) => (string) Str::of($leave->leaveType?->name ?? '')
                        ->replaceMatches('/\s+Leave\b/i', '')
                        ->trim())
                    ->filter()
                    ->unique()
                    ->sort()
                    ->values();
            @endphp

            {{-- Year filter outside the container --}}
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold text-gray-800">{{ __('My Leave Requests') }}</h1>
                    <p class="text-sm text-gray-500">{{ __('View and track your leave applications.') }}</p>
                </div>

                <div class="flex items-center gap-2">
                    <label for="my_leave_year" class="text-sm font-semibold text-gray-700">{{ __('Year') }}</label>
                    <select id="my_leave_year" x-model="year" @change="applyFilters()" class="h-10 w-28 appearance-auto rounded-lg border-gray-300 bg-white pl-4 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @for ($yearOption = $currentYear; $yearOption >= $currentYear - 4; $yearOption--)
                            <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="mb-6">
                <div class="mb-4 flex justify-end">
                    <a href="{{ route('leaves.create') }}" class="bg-blue-900 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-200 hover:-translate-y-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Apply New Leave
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
                        @foreach($credits->filter(function ($credit) {
                            $leaveName = Str::lower(trim($credit->leaveType?->name ?? ''));

                            return in_array($leaveName, [
                                'special privilege leave',
                                'sick leave',
                                'vacation leave',
                                'mandatory/force leave',
                                
                            ], true);
                        }) as $credit)
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
                <div x-show="tab === 'applications'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <!-- Search and Filters -->
                    <div class="mb-4 flex w-full flex-nowrap items-center gap-2 overflow-x-auto rounded-xl border border-gray-100 bg-gray-50/70 p-2">
                        <div class="relative min-w-[260px] flex-1">
                            <label for="my_leave_search" class="sr-only">{{ __('Search leave application') }}</label>
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M21 21l-4.35-4.35M10.75 18.5a7.75 7.75 0 100-15.5 7.75 7.75 0 000 15.5z" />
                                </svg>
                            </span>
                            <input id="my_leave_search" type="search" x-model="search" @input.debounce.200ms="applyFilters()" placeholder="{{ __('Search leave type, status, or date...') }}" class="block h-9 w-full rounded-lg border-gray-300 pl-10 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <div class="w-48 shrink-0">
                            <label for="my_leave_type" class="sr-only">{{ __('Filter by leave type') }}</label>
                            <select id="my_leave_type" x-model="leaveType" @change="applyFilters()" class="block h-9 w-full rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Leave Types') }}</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ Str::lower($type) }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-40 shrink-0">
                            <label for="my_leave_status" class="sr-only">{{ __('Filter by status') }}</label>
                            <select id="my_leave_status" x-model="status" @change="applyFilters()" class="block h-9 w-full rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Statuses') }}</option>
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="approved">{{ __('Approved') }}</option>
                                <option value="rejected">{{ __('Rejected') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                        <button type="button" @click="reset()" class="inline-flex h-9 shrink-0 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-xs font-bold uppercase tracking-wider text-gray-700 transition hover:bg-gray-50">
                            {{ __('Reset') }}
                        </button>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-[720px] w-full table-fixed divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="w-[45%] px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">Leave Type</th>
                                        <th scope="col" class="w-[18%] px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">Days</th>
                                        <th scope="col" class="w-[22%] px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-gray-500">Date Filed</th>
                                        <th scope="col" class="w-[15%] px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest text-gray-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="myLeaveApplicationTableBody" class="divide-y divide-gray-100 bg-white">
                                    @forelse($leaves as $leaf)
                                        @php
                                            $stages = [
                                                ['label' => 'HR', 'status' => $leaf->hrstaff_status],
                                                ['label' => 'Chief', 'status' => $leaf->chief_status],
                                                ['label' => 'Regional Director', 'status' => $leaf->rd_status],
                                            ];
                                            $approvedCount = collect($stages)->where('status', 'approved')->count();
                                            $hasRejected = collect($stages)->contains(fn($stage) => $stage['status'] === 'rejected');
                                            $displayStatus = $leaf->status === 'cancelled' ? 'Cancelled' : ($hasRejected ? 'Rejected' : ($approvedCount === 3 ? 'Approved' : 'Pending'));
                                            $filterStatus = $leaf->status === 'cancelled' ? 'cancelled' : ($hasRejected ? 'rejected' : ($approvedCount === 3 ? 'approved' : 'pending'));
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
                                            data-year="{{ \Carbon\Carbon::parse($leaf->date_filed)->year }}"
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
                                            <td class="px-5 py-3 text-right align-middle">
                                                <a href="{{ route('leaves.show', $leaf) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-700 transition-colors hover:bg-blue-50 hover:text-blue-900" title="{{ __('View details') }}" aria-label="{{ __('View details') }}">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center">
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
                                            <td colspan="4" class="px-6 py-12 text-center">
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
                year: '{{ now()->year }}',
                leaveType: '',
                status: '',
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

                    this.rows.forEach((row) => {
                            const matchesSearch = !search || row.dataset.search.includes(search);
                            const matchesYear = !this.year || row.dataset.year === this.year;
                            const matchesType = !this.leaveType || row.dataset.leaveType === this.leaveType;
                            const matchesStatus = !this.status || row.dataset.status === this.status;
                            const isVisible = matchesSearch && matchesYear && matchesType && matchesStatus;

                            row.classList.toggle('hidden', !isVisible);
                            if (isVisible) visibleCount++;
                        });

                    this.noResults = this.rows.length > 0 && visibleCount === 0;
                },
                reset() {
                    this.search = '';
                    this.year = '{{ now()->year }}';
                    this.leaveType = '';
                    this.status = '';
                    this.applyFilters();
                },
            };
        }
    </script>
</x-app-layout>