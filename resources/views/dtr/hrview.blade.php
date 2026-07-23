<x-app-layout>
    <x-slot name="title">{{ __('Daily Attendance Monitoring') }}</x-slot>

    <div class="py-8" x-data="{ 
        rawScansModalOpen: false, 
        selectedEmployeeName: '', 
        selectedEmployeeNo: '', 
        rawScans: [] 
    }">
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

            <!-- NEW DAILY ATTENDANCE MONITORING DASHBOARD -->
            <div class="no-print flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                        {{ __('Daily Attendance Monitoring') }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}</span>
                    </p>
                </div>

                <div class="flex flex-col items-end gap-3">
                    <div class="flex items-center gap-3">
                        @if(strtolower(auth()->user()->role ?? '') === 'admin' || strtolower(auth()->user()->role ?? '') === 'hrstaff')
                            <!-- <form method="POST" action="{{ route('dtr.import') }}">
                                @csrf
                                <button type="submit" style="background-color: #2b428f;" class="rounded-lg px-6 py-3 text-sm font-bold uppercase tracking-wide text-white shadow hover:bg-[#3a56b3] transition">
                                    {{ __('Sync/Import DTR') }}
                                </button>
                            </form> -->
                        @endif

                        <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" style="background-color: #10b981;" class="rounded-lg px-6 py-3 text-sm font-bold uppercase tracking-wide text-white shadow hover:bg-[#059669] transition">
                            {{ __('Export Daily CSV') }}
                        </a>
                    </div>

                    <div class="inline-flex rounded-xl shadow-sm border border-slate-200 bg-white p-1">
                        <a href="{{ request()->fullUrlWithQuery(['date' => \Carbon\Carbon::parse($selectedDate)->subDay()->format('Y-m-d')]) }}" class="px-4 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-lg flex items-center gap-1 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            {{ __('Prev') }}
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['date' => \Carbon\Carbon::parse($selectedDate)->addDay()->format('Y-m-d')]) }}" class="px-4 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-lg flex items-center gap-1 transition">
                            {{ __('Next') }}
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/60 flex flex-col justify-between">
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ __('Total Employees') }}</span>
                    <span class="text-2xl font-black text-slate-900 mt-2">{{ $stats['total'] }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/60 flex flex-col justify-between">
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ __('Scanned Count') }}</span>
                    <span class="text-2xl font-black text-[#2b428f] mt-2">{{ $stats['scanned'] }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/60 flex flex-col justify-between">
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ __('Present') }}</span>
                    <span class="text-2xl font-black text-emerald-600 mt-2">{{ $stats['present'] }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/60 flex flex-col justify-between">
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ __('Late') }}</span>
                    <span class="text-2xl font-black text-amber-500 mt-2">{{ $stats['late'] }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/60 flex flex-col justify-between">
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ __('In Office') }}</span>
                    <span class="text-2xl font-black text-indigo-500 mt-2">{{ $stats['in_office'] }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/60 flex flex-col justify-between">
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ __('Completed') }}</span>
                    <span class="text-2xl font-black text-blue-600 mt-2">{{ $stats['completed'] }}</span>
                </div>
            </div>

            <div class="no-print rounded-2xl bg-white p-5 shadow-sm border border-slate-200">
                <form method="GET" action="{{ route('dtr.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <input type="hidden" name="date" value="{{ $selectedDate }}">
                    
                    <div class="relative">
                        <span class="absolute -top-2.5 left-3 bg-white px-1 text-[9px] font-black uppercase tracking-wider text-slate-400">Search</span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Name, ID or RFID..." class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm outline-none focus:border-[#2b428f] focus:ring-1 focus:ring-[#2b428f]">
                    </div>

                    <div class="relative">
                        <span class="absolute -top-2.5 left-3 bg-white px-1 text-[9px] font-black uppercase tracking-wider text-slate-400">Department</span>
                        <select name="division" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm outline-none focus:border-[#2b428f] focus:ring-1 focus:ring-[#2b428f]">
                            <option value="all" {{ $divisionFilter === 'all' ? 'selected' : '' }}>All Departments</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div }}" {{ $divisionFilter === $div ? 'selected' : '' }}>{{ $div }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative">
                        <span class="absolute -top-2.5 left-3 bg-white px-1 text-[9px] font-black uppercase tracking-wider text-slate-400">Status</span>
                        <select name="status" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm outline-none focus:border-[#2b428f] focus:ring-1 focus:ring-[#2b428f]">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="Not Yet In" {{ $statusFilter === 'not yet in' ? 'selected' : '' }}>Not Yet In</option>
                            <option value="In Office" {{ $statusFilter === 'in office' ? 'selected' : '' }}>In Office</option>
                            <option value="Completed" {{ $statusFilter === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Late" {{ $statusFilter === 'late' ? 'selected' : '' }}>Late</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" style="background-color: #2b428f;" class="rounded-lg px-6 py-3 text-sm font-bold uppercase tracking-wide text-white shadow hover:bg-[#3a56b3] transition">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('dtr.index') }}?date={{ $selectedDate }}" class="rounded-lg border border-slate-350 px-6 py-3 text-sm font-bold uppercase text-slate-500 hover:bg-slate-100 transition text-center flex items-center justify-center bg-white shadow-sm">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Daily Table -->
            <div class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[9px] font-black uppercase tracking-wider text-slate-400 border-b border-slate-200">
                                <th class="px-4 py-3 text-center">Queue</th>
                                <th class="px-4 py-3">Employee</th>
                                <th class="px-4 py-3">Department</th>
                                <th class="px-4 py-3">First In</th>
                                <th class="px-4 py-3 text-center">Lunch Out</th>
                                <th class="px-4 py-3 text-center">Lunch In</th>
                                <th class="px-4 py-3">Last Out</th>
                                <th class="px-4 py-3 text-right">AHW</th>
                                <th class="px-4 py-3 text-right">Late</th>
                                <th class="px-4 py-3 text-right">Undertime</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3">Remarks</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @php $queueNumber = ($paginatedRecords->currentPage() - 1) * $paginatedRecords->perPage() + 1; @endphp
                            @forelse($paginatedRecords as $item)
                                @php
                                    $emp = $item['employee'];
                                    $displayName = $emp->firstname . ' ' . $emp->lastname;
                                    $empNo = $emp->employee_no ?? ('EMP-' . str_pad((string) $emp->id, 4, '0', STR_PAD_LEFT));
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 text-center font-bold text-slate-400">
                                        {{ $item['time_in'] ? $queueNumber++ : '—' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm leading-tight">{{ $displayName }}</p>
                                            <p class="text-[9px] font-semibold text-slate-400 mt-0.5">ID: {{ $empNo }} | RFID: {{ $emp->rfid_number ?: 'N/A' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-600">{{ $emp->division ?: '—' }}</td>
                                    <td class="px-4 py-3 font-bold text-slate-900">
                                        {{ $item['time_in'] ? \Carbon\Carbon::parse($item['time_in'])->format('h:i A') : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-slate-300 italic">—</td>
                                    <td class="px-4 py-3 text-center text-slate-300 italic">—</td>
                                    <td class="px-4 py-3 font-bold text-slate-900">
                                        {{ $item['time_out'] ? \Carbon\Carbon::parse($item['time_out'])->format('h:i A') : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-black italic text-slate-950">
                                        {{ $item['hours_worked'] }} hrs
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold {{ $item['late_minutes'] > 0 ? 'text-amber-600 font-bold' : 'text-slate-400' }}">
                                        {{ $item['late_minutes'] > 0 ? $item['late_minutes'] . 'm' : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold {{ $item['undertime_minutes'] > 0 ? 'text-rose-500 font-bold' : 'text-slate-400' }}">
                                        {{ $item['undertime_minutes'] > 0 ? $item['undertime_minutes'] . 'm' : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider border',
                                            'bg-emerald-50 text-emerald-600 border-emerald-100' => $item['status'] === 'In Office',
                                            'bg-blue-50 text-blue-600 border-blue-100' => $item['status'] === 'Completed',
                                            'bg-amber-50 text-amber-600 border-amber-100' => $item['status'] === 'Late',
                                            'bg-slate-50 text-slate-400 border-slate-100' => $item['status'] === 'Not Yet In',
                                        ])>
                                            {{ $item['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-slate-500">{{ $item['remarks'] ?: '—' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $scans = [];
                                            if ($item['time_in']) {
                                                $scans[] = [
                                                    'time' => \Carbon\Carbon::parse($item['time_in'])->format('h:i A'),
                                                    'type' => 'IN',
                                                    'device' => 'RFID Reader #1',
                                                    'status' => $item['late_minutes'] > 0 ? 'Late Arrival' : 'Valid'
                                                ];
                                            }
                                            if ($item['time_out']) {
                                                $scans[] = [
                                                    'time' => \Carbon\Carbon::parse($item['time_out'])->format('h:i A'),
                                                    'type' => 'OUT',
                                                    'device' => 'RFID Reader #1',
                                                    'status' => 'Valid'
                                                ];
                                            }
                                            $jsonScans = json_encode($scans);
                                        @endphp
                                        <button type="button" @click="
                                            rawScans = {{ $jsonScans }};
                                            selectedEmployeeName = '{{ $displayName }}';
                                            selectedEmployeeNo = '{{ $empNo }}';
                                            rawScansModalOpen = true;
                                        " class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold uppercase text-[9px] rounded-lg tracking-wider transition">
                                            {{ __('View') }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="px-6 py-12 text-center text-slate-400">
                                        <p class="text-sm font-semibold">{{ __('No attendance records found.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($paginatedRecords->hasPages())
                    <div class="bg-slate-50 border-t border-slate-250 px-4 py-3">
                        {{ $paginatedRecords->links() }}
                    </div>
                @endif
            </div>

            <!-- Side Panel/Modal for Raw Scan History -->
            <div x-show="rawScansModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="rawScansModalOpen = false"></div>
                <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ __('Raw RFID Scan History') }}</h3>
                            <h2 class="text-lg font-bold text-slate-900" x-text="selectedEmployeeName"></h2>
                            <p class="text-[9px] font-semibold text-slate-400">Employee ID: <span x-text="selectedEmployeeNo"></span> | Date: {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</p>
                        </div>
                        <button type="button" @click="rawScansModalOpen = false" class="text-slate-400 hover:text-slate-600 rounded-lg p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <template x-if="rawScans.length === 0">
                                <div class="text-center py-6 text-slate-400">
                                    <p class="text-sm font-medium">No raw RFID scan history available for this date.</p>
                                </div>
                            </template>

                            <template x-if="rawScans.length > 0">
                                <div class="divide-y divide-slate-100 border border-slate-200 rounded-xl overflow-hidden">
                                    <div class="grid grid-cols-4 bg-slate-50 text-[9px] font-black uppercase tracking-wider text-slate-400 px-4 py-2 border-b border-slate-200">
                                        <div>Time</div>
                                        <div>Type</div>
                                        <div>Device</div>
                                        <div class="text-right">Status</div>
                                    </div>
                                    
                                    <template x-for="scan in rawScans">
                                        <div class="grid grid-cols-4 items-center px-4 py-3 text-xs">
                                            <div class="font-bold text-slate-900" x-text="scan.time"></div>
                                            <div>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold uppercase text-center" :class="scan.type === 'IN' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-blue-50 text-blue-700 border border-blue-100'" x-text="scan.type"></span>
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
</x-app-layout>
