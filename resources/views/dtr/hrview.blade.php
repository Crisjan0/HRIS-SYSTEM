<x-app-layout>
    <x-slot name="title">{{ __('Daily Attendance Monitoring') }}</x-slot>

    <div
        class="py-8"
        x-data="attendanceMonitor({
            search: @js($search),
            division: @js($divisionFilter),
            employmentStatus: @js($employmentStatusFilter),
            date: @js($selectedDate),
        })"
        x-init="init()"
    >
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="no-print rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="no-print rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-bold text-red-700 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div
                x-data="{
                    rawScansModalOpen: false,
                    selectedEmployeeName: '',
                    selectedEmployeeNo: '',
                    selectedDepartment: '',
                    selectedPosition: '',
                    selectedEmploymentStatus: '',
                    selectedRfid: '',
                    selectedStatus: '',
                    selectedHoursWorked: '',
                    selectedLateMinutes: '',
                    selectedUndertimeMinutes: '',
                    selectedRemarks: '',
                    rawScans: []
                }"
            >
                <div class="no-print flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h1 class="text-2xl font-black tracking-tight text-slate-900">
                            {{ __('Daily Attendance Monitoring') }}
                        </h1>
                        <p class="mt-1 flex items-center gap-2 text-sm font-medium text-slate-500">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}</span>
                        </p>
                    </div>

                    <div class="flex flex-col items-end gap-3">
                        <div class="flex items-center gap-2">
                            <label for="attendance_year" class="text-sm font-semibold text-slate-700">
                                {{ __('Year') }}
                            </label>

                            @php
                                $currentYear = now()->year;
                                $selectedYear = \Carbon\Carbon::parse($selectedDate)->year;
                            @endphp

                            <div class="relative">
                                <select
                                    id="attendance_year"
                                    x-model="year"
                                    @change="changeYear()"
                                    class="h-10 w-28 appearance-none rounded-lg border-slate-300 bg-white pl-4 pr-9 text-sm shadow-sm focus:border-[#2b428f] focus:ring-[#2b428f]"
                                >
                                    @for ($yearOption = $currentYear; $yearOption >= $currentYear - 4; $yearOption--)
                                        <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                                    @endfor

                                    @if($selectedYear > $currentYear || $selectedYear < $currentYear - 4)
                                        <option value="{{ $selectedYear }}">{{ $selectedYear }}</option>
                                    @endif
                                </select>

                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4 inline-flex rounded-xl border border-slate-200 bg-white p-1 shadow-sm">
                            <a href="{{ request()->fullUrlWithQuery(['date' => \Carbon\Carbon::parse($selectedDate)->subDay()->format('Y-m-d')]) }}" class="flex items-center gap-1 rounded-lg px-4 py-1.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                {{ __('Prev') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['date' => \Carbon\Carbon::parse($selectedDate)->addDay()->format('Y-m-d')]) }}" class="flex items-center gap-1 rounded-lg px-4 py-1.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                {{ __('Next') }}
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm">
                        <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">
                            {{ __('Total Employees') }}
                        </span>
                        <span class="mt-2 text-2xl font-black text-slate-900">
                            {{ $stats['total'] }}
                        </span>
                    </div>

                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm">
                        <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">
                            {{ __('Present') }}
                        </span>
                        <span class="mt-2 text-2xl font-black text-emerald-600">
                            {{ $stats['present'] }}
                        </span>
                    </div>

                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm">
                        <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">
                            {{ __('Late') }}
                        </span>
                        <span class="mt-2 text-2xl font-black text-amber-500">
                            {{ $stats['late'] }}
                        </span>
                    </div>

                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200/60 bg-white p-4 shadow-sm">
                        <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">
                            {{ __('Completed') }}
                        </span>
                        <span class="mt-2 text-2xl font-black text-blue-600">
                            {{ $stats['completed'] }}
                        </span>
                    </div>
                </div>

                <div class="no-print border-b border-slate-200 bg-slate-50/70 p-2">
                    <div class="flex min-w-0 flex-col gap-2 sm:flex-row sm:items-center">
                        <div class="relative min-w-0 sm:flex-1">
                            <label for="attendance_search" class="sr-only">{{ __('Search attendance records') }}</label>
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m1.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                </svg>
                            </span>
                            <input
                                id="attendance_search"
                                type="search"
                                x-model="search"
                                @input.debounce.200ms="applyFilters()"
                                placeholder="{{ __('Search employee, department, position, or ID...') }}"
                                class="block h-9 w-full rounded-lg border-slate-300 bg-white pl-10 pr-3 text-sm shadow-sm focus:border-[#2b428f] focus:ring-[#2b428f]"
                            />
                        </div>

                        <select
                            x-model="division"
                            @change="applyFilters()"
                            class="block h-9 rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-[#2b428f] focus:ring-[#2b428f] sm:w-48"
                        >
                            <option value="all">{{ __('Departments') }}</option>
                            @foreach($divisions as $divisionOption)
                                <option value="{{ $divisionOption }}">{{ $divisionOption }}</option>
                            @endforeach
                        </select>

                        <select
                            x-model="employmentStatus"
                            @change="applyFilters()"
                            class="block h-9 rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-[#2b428f] focus:ring-[#2b428f] sm:w-48"
                        >
                            <option value="all">{{ __('Status') }}</option>
                            @foreach($employmentStatuses as $employmentStatusOption)
                                <option value="{{ $employmentStatusOption }}">{{ $employmentStatusOption }}</option>
                            @endforeach
                        </select>

                        <button
                            type="button"
                            @click="reset()"
                            class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-300 bg-white px-4 text-xs font-bold uppercase tracking-wider text-slate-700 transition hover:bg-slate-100"
                        >
                            {{ __('Reset') }}
                        </button>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left text-[11px] leading-tight">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 text-[9px] font-black uppercase tracking-wider text-slate-400">
                                    <th class="px-3 py-3">Employee</th>
                                    <th class="px-3 py-3">Department</th>
                                    <th class="px-3 py-3">Position</th>
                                    <th class="px-3 py-3">AM In</th>
                                    <th class="px-3 py-3">AM Out</th>
                                    <th class="px-3 py-3">PM In</th>
                                    <th class="px-3 py-3">PM Out</th>
                                    <th class="px-3 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse($attendanceRecords as $item)
                                    @php
                                        $employee = $item['employee'];
                                        $displayName = trim(($employee->firstname ?? '') . ' ' . ($employee->lastname ?? ''));
                                        $employeeNumber = $employee->employee_no ?? ('EMP-' . str_pad((string) $employee->id, 4, '0', STR_PAD_LEFT));
                                        $searchText = Str::lower(implode(' ', [
                                            $displayName,
                                            $employee->division ?? '',
                                            $employee->position ?? '',
                                            $employee->employment_status ?? '',
                                            $employeeNumber,
                                        ]));
                                        $scans = [];

                                        if ($item['time_in']) {
                                            $scans[] = [
                                                'time' => \Carbon\Carbon::parse($item['time_in'])->format('h:i A'),
                                                'type' => 'IN',
                                                'device' => 'RFID Reader #1',
                                                'status' => $item['late_minutes'] > 0 ? 'Late Arrival' : 'Valid',
                                            ];
                                        }

                                        if ($item['time_out']) {
                                            $scans[] = [
                                                'time' => \Carbon\Carbon::parse($item['time_out'])->format('h:i A'),
                                                'type' => 'OUT',
                                                'device' => 'RFID Reader #1',
                                                'status' => 'Valid',
                                            ];
                                        }
                                    @endphp
                                    <tr
                                        class="transition hover:bg-slate-50/50"
                                        data-attendance-row
                                        data-search="{{ $searchText }}"
                                        data-division="{{ Str::lower($employee->division ?? 'n/a') }}"
                                        data-employment-status="{{ Str::lower($employee->employment_status ?? 'n/a') }}"
                                    >
                                        <td class="px-3 py-3 align-top">
                                            <div class="min-w-[12rem] max-w-[14rem] whitespace-normal break-words">
                                                <p class="text-[11px] font-bold leading-tight text-slate-900">{{ $displayName ?: 'N/A' }}</p>
                                                <p class="mt-0.5 text-[9px] font-semibold uppercase tracking-wider text-slate-400">{{ $employee->employment_status ?: 'N/A' }}</p>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 align-top text-[11px] font-semibold text-slate-600">
                                            <div class="max-w-[11rem] whitespace-normal break-words">{{ $employee->division ?: 'N/A' }}</div>
                                        </td>
                                        <td class="px-3 py-3 align-top text-[11px] font-semibold text-slate-600">
                                            <div class="max-w-[11rem] whitespace-normal break-words">{{ $employee->position ?: 'N/A' }}</div>
                                        </td>
                                        <td class="px-3 py-3 align-top text-[11px] font-bold text-slate-900 whitespace-normal break-words">{{ $item['time_in'] ? \Carbon\Carbon::parse($item['time_in'])->format('h:i A') : 'N/A' }}</td>
                                        <td class="px-3 py-3 align-top text-[11px] font-bold text-slate-900 whitespace-normal break-words"></td>
                                        <td class="px-3 py-3 align-top text-[11px] font-bold text-slate-900 whitespace-normal break-words"></td>
                                        <td class="px-3 py-3 align-top text-[11px] font-bold text-slate-900 whitespace-normal break-words">{{ $item['time_out'] ? \Carbon\Carbon::parse($item['time_out'])->format('h:i A') : 'N/A' }}</td>
                                        <td class="px-3 py-3 text-center align-top">
                                            <button
                                                type="button"
                                                @click="
                                                    rawScans = @js($scans);
                                                    selectedEmployeeName = @js($displayName);
                                                    selectedEmployeeNo = @js($employeeNumber);
                                                    selectedDepartment = @js($employee->division ?: 'N/A');
                                                    selectedPosition = @js($employee->position ?: 'N/A');
                                                    selectedEmploymentStatus = @js($employee->employment_status ?: 'N/A');
                                                    selectedRfid = @js($employee->rfid_number ?: 'N/A');
                                                    selectedStatus = @js($item['status']);
                                                    selectedHoursWorked = @js($item['hours_worked'] . ' hrs');
                                                    selectedLateMinutes = @js($item['late_minutes'] > 0 ? $item['late_minutes'] . 'm' : 'N/A');
                                                    selectedUndertimeMinutes = @js($item['undertime_minutes'] > 0 ? $item['undertime_minutes'] . 'm' : 'N/A');
                                                    selectedRemarks = @js($item['remarks'] ?: 'N/A');
                                                    rawScansModalOpen = true;
                                                "
                                                class="rounded-lg bg-slate-100 px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider text-slate-700 transition hover:bg-slate-200"
                                            >
                                                {{ __('View') }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                            <p class="text-sm font-semibold">{{ __('No attendance records found.') }}</p>
                                        </td>
                                    </tr>
                                @endforelse

                                @if($attendanceRecords->isNotEmpty())
                                    <tr x-show="noResults" style="display: none;">
                                        <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                            <p class="text-sm font-semibold">{{ __('No attendance records match the selected filters.') }}</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div x-show="rawScansModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="rawScansModalOpen = false"></div>
                    <div class="relative w-full max-w-3xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
                        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-6 py-4">
                            <div>
                                <h3 class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ __('Attendance Details') }}</h3>
                                <h2 class="text-lg font-bold text-slate-900" x-text="selectedEmployeeName"></h2>
                                <p class="text-[9px] font-semibold text-slate-400">Employee ID: <span x-text="selectedEmployeeNo"></span> | Date: {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</p>
                            </div>
                            <button type="button" @click="rawScansModalOpen = false" class="rounded-lg p-1 text-slate-400 hover:text-slate-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-6">
                            <div class="space-y-5">
                                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Department</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900" x-text="selectedDepartment"></p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Position</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900" x-text="selectedPosition"></p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Employment Status</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900" x-text="selectedEmploymentStatus"></p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">RFID Number</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900" x-text="selectedRfid"></p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Attendance Status</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900" x-text="selectedStatus"></p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Worked Hours</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900" x-text="selectedHoursWorked"></p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Late</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900" x-text="selectedLateMinutes"></p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Undertime</p>
                                        <p class="mt-1 text-sm font-bold text-slate-900" x-text="selectedUndertimeMinutes"></p>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">
                                    <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">Remarks</p>
                                    <p class="mt-1 text-sm font-medium text-slate-700" x-text="selectedRemarks"></p>
                                </div>

                                <template x-if="rawScans.length === 0">
                                    <div class="py-6 text-center text-slate-400">
                                        <p class="text-sm font-medium">No raw RFID scan history available for this date.</p>
                                    </div>
                                </template>

                                <template x-if="rawScans.length > 0">
                                    <div class="overflow-hidden rounded-xl border border-slate-200">
                                        <div class="grid grid-cols-4 border-b border-slate-200 bg-slate-50 px-4 py-2 text-[9px] font-black uppercase tracking-wider text-slate-400">
                                            <div>Time</div>
                                            <div>Type</div>
                                            <div>Device</div>
                                            <div class="text-right">Status</div>
                                        </div>

                                        <template x-for="scan in rawScans" :key="scan.time + scan.type">
                                            <div class="grid grid-cols-4 items-center border-b border-slate-100 px-4 py-3 text-xs last:border-b-0">
                                                <div class="font-bold text-slate-900" x-text="scan.time"></div>
                                                <div>
                                                    <span class="inline-flex items-center rounded px-2 py-0.5 text-center text-[9px] font-bold uppercase" :class="scan.type === 'IN' ? 'border border-emerald-100 bg-emerald-50 text-emerald-700' : 'border border-blue-100 bg-blue-50 text-blue-700'" x-text="scan.type"></span>
                                                </div>
                                                <div class="text-slate-500" x-text="scan.device"></div>
                                                <div class="text-right font-semibold" :class="scan.status === 'Valid' ? 'text-emerald-600' : 'text-amber-600'" x-text="scan.status"></div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function attendanceMonitor(config) {
            return {
                search: config.search || '',
                division: config.division || 'all',
                employmentStatus: config.employmentStatus || 'all',
                date: config.date,
                year: String(new Date(`${config.date}T00:00:00`).getFullYear()),
                rows: [],
                noResults: false,

                init() {
                    this.rows = Array.from(document.querySelectorAll('[data-attendance-row]'));
                    this.applyFilters();
                },

                applyFilters() {
                    const search = this.search.trim().toLowerCase();
                    let visibleCount = 0;

                    this.rows.forEach((row) => {
                        const rowSearch = row.dataset.search || '';
                        const rowDivision = row.dataset.division || 'n/a';
                        const rowEmploymentStatus = row.dataset.employmentStatus || 'n/a';

                        const visible =
                            (!search || rowSearch.includes(search)) &&
                            (this.division === 'all' || rowDivision === this.division.toLowerCase()) &&
                            (this.employmentStatus === 'all' || rowEmploymentStatus === this.employmentStatus.toLowerCase());

                        row.classList.toggle('hidden', !visible);

                        if (visible) {
                            visibleCount++;
                        }
                    });

                    this.noResults = this.rows.length > 0 && visibleCount === 0;
                },



                changeYear() {
                    const currentDate = new Date(`${this.date}T00:00:00`);
                    const selectedYear = Number(this.year);
                    const month = currentDate.getMonth();
                    const day = currentDate.getDate();

                    const targetDate = new Date(selectedYear, month, day);

                    // Handle dates such as February 29 when the selected year is not a leap year.
                    if (targetDate.getMonth() !== month) {
                        targetDate.setDate(0);
                    }

                    const formattedDate = [
                        targetDate.getFullYear(),
                        String(targetDate.getMonth() + 1).padStart(2, '0'),
                        String(targetDate.getDate()).padStart(2, '0'),
                    ].join('-');

                    const url = new URL(window.location.href);
                    url.searchParams.set('date', formattedDate);
                    window.location.href = url.toString();
                },


                reset() {
                    this.search = '';
                    this.division = 'all';
                    this.employmentStatus = 'all';
                    this.applyFilters();
                },
            };
        }
    </script>
</x-app-layout>