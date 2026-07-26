<x-app-layout>
    <x-slot name="title">{{ __('Daily Time Records (DTR)') }}</x-slot>

    @php
        $user = auth()->user();
        $employee = $user->employee;
        $isAdministrativeDtr = ! ($isPersonal ?? false) && in_array(strtolower($user->role ?? ''), ['admin', 'hrstaff', 'chief', 'regionaldirector'], true);
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
        ];
        $startYear = now()->year - 3;
        $endYear = now()->year + 1;
        $selectedDate = \Carbon\Carbon::create((int) $selectedYear, (int) $selectedMonth, 1);
        $calendarDays = collect(range(1, $selectedDate->daysInMonth))->map(fn ($day) => $selectedDate->copy()->day($day));
        $recordsByDate = collect($dtrRecords ?? [])->groupBy(fn ($record) => \Carbon\Carbon::parse($record->date)->format('Y-m-d'));
        $displayEmployeeNo = $employee?->employee_no ?? ($employee?->id ? 'EMP-' . str_pad((string) $employee->id, 4, '0', STR_PAD_LEFT) : 'N/A');
        $statusText = fn ($record) => (string) ($record->status ?: 'Present');
        $formatTime = fn ($time) => $time ? \Carbon\Carbon::parse($time)->format('h:i A') : '';
        $recordHours = function ($record) {
            if (! $record->time_in || ! $record->time_out) {
                return '';
            }

            $hours = \Carbon\Carbon::parse($record->time_in)->diffInMinutes(\Carbon\Carbon::parse($record->time_out)) / 60;

            return number_format($hours, 2);
        };
    @endphp

    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            body {
                background: #fff !important;
            }

            .no-print,
            nav,
            aside,
            header {
                display: none !important;
            }

            .dtr-print-shell {
                padding: 0 !important;
                margin: 0 !important;
            }

            .dtr-sheet {
                width: 100% !important;
                min-width: 0 !important;
                border: 0 !important;
                box-shadow: none !important;
                padding: 0 !important;
            }

            .dtr-sheet table {
                font-size: 9px !important;
            }

            .dtr-sheet th,
            .dtr-sheet td {
                padding: 3px 4px !important;
            }

            .dtr-late-summary,
            .dtr-signatures {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>

    <div class="py-8">
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

            <div class="no-print flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">
                        {{ __('My Daily Time Record') }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ __('Review and print your monthly DTR.') }}
                    </p>
                </div>
            </div>

            <div class="no-print rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <form method="GET" action="{{ route('my-dtr.index') }}" class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-1 flex-col gap-4 sm:flex-row">
                        <label class="relative block flex-1">
                            <span class="absolute -top-2 left-3 bg-white px-1 text-xs text-slate-500">Month</span>
                            <select name="month" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-[#2b428f]">
                                @foreach($months as $num => $name)
                                    <option value="{{ $num }}" {{ (int) $selectedMonth === $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="relative block flex-1">
                            <span class="absolute -top-2 left-3 bg-white px-1 text-xs text-slate-500">Year</span>
                            <select name="year" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-[#2b428f]">
                                @for($year = $startYear; $year <= $endYear; $year++)
                                    <option value="{{ $year }}" {{ (int) $selectedYear === $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </label>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" style="background-color: #2b428f;" class="rounded-lg px-6 py-3 text-sm font-bold uppercase tracking-wide text-white shadow">
                            {{ __('Select') }}
                        </button>
                        <button type="button" onclick="window.print()" style="background-color: #2b428f;" class="rounded-lg px-6 py-3 text-sm font-bold uppercase tracking-wide text-white shadow">
                            {{ __('Print') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="dtr-print-shell rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="overflow-x-auto">
                    <div class="dtr-sheet min-w-[1120px] rounded-lg border border-slate-200 bg-white p-5">
                        <div class="text-center">
                            <h2 class="text-base font-bold text-slate-900">
                                Department of Migrant Workers - Region XI
                            </h2>
                            <p class="text-xs text-slate-600">
                                Davao City, Philippines
                            </p>
                            <p class="text-xs text-slate-600">
                                Website: www.dmw.gov.ph | Email: region11@dmw.gov.ph
                            </p>
                            <h3 class="mt-4 text-base font-bold text-slate-900">
                                Daily Time Record for the period of {{ $dtrSummary['period'] }}
                            </h3>
                        </div>

                        <div class="mt-5 grid gap-4 text-xs text-slate-700 md:grid-cols-2">
                            <div>
                                <p class="font-semibold">Legend</p>
                                <p class="mt-1">AHW - Actual Hours Worked &nbsp;&nbsp; OHW - Official Hours Worked</p>
                                <p>OT - Overtime &nbsp;&nbsp; LT - Lates &nbsp;&nbsp; UT - Undertime</p>
                            </div>

                            <div class="md:text-right">
                                <p>Employee No. <span class="font-bold underline">{{ $displayEmployeeNo }}</span></p>
                                <p>Name: <span class="font-bold uppercase">{{ trim(($employee?->firstname ?? '') . ' ' . ($employee?->lastname ?? '')) ?: $user->name }}</span></p>
                            </div>
                        </div>

                        <div class="mt-5 overflow-x-auto">
                            <table class="w-full border-collapse text-center text-[11px] leading-tight">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th rowspan="2" class="border border-slate-300 px-2 py-2">Date</th>
                                        <th rowspan="2" class="border border-slate-300 px-2 py-2">Day</th>
                                        <th colspan="2" class="border border-slate-300 px-2 py-2">Morning</th>
                                        <th colspan="2" class="border border-slate-300 px-2 py-2">Afternoon</th>
                                        <th colspan="2" class="border border-slate-300 px-2 py-2">Overtime</th>
                                        <th rowspan="2" class="border border-slate-300 px-2 py-2">AHW</th>
                                        <th rowspan="2" class="border border-slate-300 px-2 py-2">OHW</th>
                                        <th rowspan="2" class="border border-slate-300 px-2 py-2">OT</th>
                                        <th rowspan="2" class="border border-slate-300 px-2 py-2">LT</th>
                                        <th rowspan="2" class="border border-slate-300 px-2 py-2">UT</th>
                                        <th rowspan="2" class="border border-slate-300 px-2 py-2">Remarks</th>
                                    </tr>
                                    <tr class="bg-slate-50">
                                        <th class="border border-slate-300 px-2 py-2">In</th>
                                        <th class="border border-slate-300 px-2 py-2">Out</th>
                                        <th class="border border-slate-300 px-2 py-2">In</th>
                                        <th class="border border-slate-300 px-2 py-2">Out</th>
                                        <th class="border border-slate-300 px-2 py-2">In</th>
                                        <th class="border border-slate-300 px-2 py-2">Out</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($calendarDays as $day)
                                        @php
                                            $record = $recordsByDate->get($day->format('Y-m-d'), collect())->first();
                                            $isWeekend = $day->isWeekend();
                                            $hours = $record ? $recordHours($record) : '';
                                            $isLate = $record && strtolower($statusText($record)) === 'late';
                                            $remarks = $record ? $statusText($record) : ($isWeekend ? 'Weekend' : '');
                                        @endphp
                                        <tr class="hover:bg-slate-50">
                                            <td class="border border-slate-300 px-2 py-1">{{ $day->day }}</td>
                                            <td class="border border-slate-300 px-2 py-1">{{ $day->format('D') }}</td>
                                            <td class="border border-slate-300 px-2 py-1">{{ $record ? $formatTime($record->time_in) : '' }}</td>
                                            <td class="border border-slate-300 px-2 py-1"></td>
                                            <td class="border border-slate-300 px-2 py-1"></td>
                                            <td class="border border-slate-300 px-2 py-1">{{ $record ? $formatTime($record->time_out) : '' }}</td>
                                            <td class="border border-slate-300 px-2 py-1"></td>
                                            <td class="border border-slate-300 px-2 py-1"></td>
                                            <td class="border border-slate-300 px-2 py-1">{{ $hours }}</td>
                                            <td class="border border-slate-300 px-2 py-1">{{ $isWeekend ? '' : '8.00' }}</td>
                                            <td class="border border-slate-300 px-2 py-1"></td>
                                            <td class="border border-slate-300 px-2 py-1">{{ $isLate ? 'Late' : '' }}</td>
                                            <td class="border border-slate-300 px-2 py-1"></td>
                                            <td class="border border-slate-300 px-2 py-1 text-left">{{ $remarks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="dtr-late-summary mt-8 rounded-lg border border-slate-300 bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-sm font-bold uppercase tracking-wide text-slate-900">
                                        Late Dates Summary
                                    </h4>
                                    <p class="mt-1 text-xs text-slate-600">
                                        This section helps the employee and HR quickly review dates with late attendance for the selected month.
                                    </p>
                                </div>

                                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-200">
                                    {{ $lateRecords->count() }} late day(s)
                                </span>
                            </div>

                            <div class="mt-3 overflow-x-auto">
                                <table class="w-full border-collapse text-xs">
                                    <thead>
                                        <tr class="bg-white text-left text-slate-600">
                                            <th class="border border-slate-300 px-2 py-2">Date</th>
                                            <th class="border border-slate-300 px-2 py-2">Day</th>
                                            <th class="border border-slate-300 px-2 py-2">Time In</th>
                                            <th class="border border-slate-300 px-2 py-2">Late</th>
                                            <th class="border border-slate-300 px-2 py-2">Remarks</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($lateRecords as $lateRecord)
                                            @php $lateDate = \Carbon\Carbon::parse($lateRecord->date); @endphp
                                            <tr class="bg-white">
                                                <td class="border border-slate-300 px-2 py-1">{{ $lateDate->format('M d, Y') }}</td>
                                                <td class="border border-slate-300 px-2 py-1">{{ $lateDate->format('D') }}</td>
                                                <td class="border border-slate-300 px-2 py-1">{{ $formatTime($lateRecord->time_in) ?: 'N/A' }}</td>
                                                <td class="border border-slate-300 px-2 py-1">Late</td>
                                                <td class="border border-slate-300 px-2 py-1">{{ $lateRecord->status ?: 'Late arrival' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="border border-slate-300 px-2 py-3 text-center text-slate-500">
                                                    No late records for this selected month.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="dtr-signatures mt-12 grid gap-12 text-sm md:grid-cols-2">
                            <div class="text-center">
                                <div class="mx-auto flex h-16 w-64 items-end justify-center">
                                    @if($employee?->effective_signature_url)
                                        <img src="{{ $employee->effective_signature_url }}" alt="Employee e-signature" class="max-h-14 object-contain">
                                    @else
                                        <span class="text-xs italic text-slate-400">Employee e-signature</span>
                                    @endif
                                </div>

                                <div class="mx-auto w-64 border-t border-slate-400 pt-2">
                                    <p class="font-semibold">Employee Signature</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ trim(($employee?->firstname ?? '') . ' ' . ($employee?->lastname ?? '')) ?: $user->name }}</p>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="mx-auto flex h-16 w-64 items-end justify-center">
                                    <span class="text-xs italic text-slate-400">HR e-signature</span>
                                </div>

                                <div class="mx-auto w-64 border-t border-slate-400 pt-2">
                                    <p class="font-semibold">HR Verification</p>
                                    <p class="mt-1 text-xs text-slate-500">Authorized HR Personnel</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
