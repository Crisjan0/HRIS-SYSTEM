<x-app-layout>
    <x-slot name="title">{{ __('Daily Time Records (DTR)') }}</x-slot>

    <div class="py-12" x-data="{ printPreviewOpen: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-[1.5rem] shadow-sm flex items-center animate-fade-in">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-[1.5rem] shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="font-bold text-sm">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-2xl shadow-indigo-50 sm:rounded-[2.5rem] border border-gray-100">
                <div class="p-10">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('Daily Time Record') }}</h3>
                            <p class="text-sm text-gray-500 font-medium">{{ __('Printable personal DTR copy preview.') }}</p>
                        </div>
                        <button type="button" @click="printPreviewOpen = true" class="inline-flex w-fit items-center gap-2 rounded-xl bg-[#0038a8] px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-md shadow-blue-100 hover:bg-[#002f8f] transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v-6H6v6z"></path>
                            </svg>
                            {{ __('Print DTR Copy') }}
                        </button>
                    </div>

                    @if(auth()->user()->hasRole('hrstaff') || auth()->user()->hasRole('admin'))
                    <!-- Stats Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                        <div class="bg-gradient-to-br from-indigo-50 to-white p-8 rounded-3xl border border-indigo-100/50 group hover:shadow-lg transition-all duration-300">
                            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">{{ __('Total Records') }}</p>
                            <h4 class="text-3xl font-black text-gray-900 italic">{{ $records->total() }}</h4>
                        </div>
                        <div class="bg-gradient-to-br from-emerald-50 to-white p-8 rounded-3xl border border-emerald-100/50 group hover:shadow-lg transition-all duration-300">
                            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">{{ __('Latest Sync') }}</p>
                            <h4 class="text-xl font-black text-gray-900 uppercase tracking-tight">{{ now()->format('M d, h:i A') }}</h4>
                        </div>
                        <div class="bg-gradient-to-br from-amber-50 to-white p-8 rounded-3xl border border-amber-100/50 group hover:shadow-lg transition-all duration-300">
                            <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">{{ __('File Source') }}</p>
                            <h4 class="text-xl font-black text-gray-900 italic underline decoration-amber-200 decoration-4 underline-offset-4 tracking-tighter">dtr.xlsx</h4>
                        </div>
                    </div>
                    @endif
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-4">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                    <th class="px-6 pb-2">{{ __('Employee') }}</th>
                                    <th class="px-6 pb-2">{{ __('Date') }}</th>
                                    <th class="px-6 pb-2">{{ __('Time In') }}</th>
                                    <th class="px-6 pb-2">{{ __('Time Out') }}</th>
                                    <th class="px-6 pb-2">{{ __('Status') }}</th>
                                    <th class="px-6 pb-2 text-right">{{ __('Hours') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                    <tr class="group hover:-translate-y-1 transition-all duration-300">
                                        <td class="px-6 py-5 bg-gray-50/50 rounded-l-[1.5rem] group-hover:bg-white border-y border-l border-transparent group-hover:border-gray-100 group-hover:shadow-sm">
                                            <div class="flex items-center gap-3">
                                                <x-profile-avatar :employee="$record->employee" size="md" variant="indigo" rounded="2xl" class="shadow-lg shadow-indigo-100 group-hover:scale-110 transition-transform" />
                                                <div>
                                                    <p class="text-sm font-black text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $record->employee->firstname ?? 'Unknown' }} {{ $record->employee->lastname ?? '' }}</p>
                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ID: {{ $record->employee_id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 bg-gray-50/50 group-hover:bg-white border-y border-transparent group-hover:border-gray-100 group-hover:shadow-sm">
                                            <p class="text-sm font-bold text-gray-700 leading-none mb-1">{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</p>
                                            <p class="text-[10px] text-gray-400 font-medium tracking-tighter uppercase">{{ \Carbon\Carbon::parse($record->date)->format('l') }}</p>
                                        </td>
                                        <td class="px-6 py-5 bg-gray-50/50 group-hover:bg-white border-y border-transparent group-hover:border-gray-100 group-hover:shadow-sm">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                                <span class="text-sm font-black text-gray-900 uppercase">{{ \Carbon\Carbon::parse($record->time_in)->format('h:i A') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 bg-gray-50/50 group-hover:bg-white border-y border-transparent group-hover:border-gray-100 group-hover:shadow-sm">
                                            @if($record->time_out)
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                                                    <span class="text-sm font-black text-gray-900 uppercase">{{ \Carbon\Carbon::parse($record->time_out)->format('h:i A') }}</span>
                                                </div>
                                            @else
                                                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">{{ __('No Out') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 bg-gray-50/50 group-hover:bg-white border-y border-transparent group-hover:border-gray-100 group-hover:shadow-sm">
                                            <span @class([
                                                'inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.1em] border',
                                                'bg-emerald-50 text-emerald-600 border-emerald-100' => $record->status === 'Present',
                                                'bg-amber-50 text-amber-600 border-amber-100' => $record->status === 'Late',
                                                'bg-rose-50 text-rose-600 border-rose-100' => $record->status === 'Absent',
                                                'bg-gray-50 text-gray-600 border-gray-100' => !in_array($record->status, ['Present', 'Late', 'Absent']),
                                            ])>
                                                {{ __($record->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 bg-gray-50/50 rounded-r-[1.5rem] group-hover:bg-white border-y border-r border-transparent group-hover:border-gray-100 group-hover:shadow-sm text-right">
                                            @if($record->time_in && $record->time_out)
                                                @php
                                                    $in = \Carbon\Carbon::parse($record->time_in);
                                                    $out = \Carbon\Carbon::parse($record->time_out);
                                                    $hours = $in->diffInMinutes($out) / 60;
                                                @endphp
                                                <span class="text-sm font-black text-gray-900 italic tracking-tighter">{{ number_format($hours, 2) }} {{ Str::plural('hr', $hours) }}</span>
                                            @else
                                                <span class="text-gray-300 font-medium italic">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-20 text-center bg-gray-50/30 rounded-[2.5rem] border-2 border-dashed border-gray-100 mt-8">
                                            <div class="flex flex-col items-center">
                                                <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center shadow-lg shadow-gray-100 mb-6">
                                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <h4 class="text-xl font-black text-gray-900 italic uppercase tracking-tight mb-2">{{ __('No DTR logs found') }}</h4>
                                                <p class="text-gray-400 font-medium max-w-xs mx-auto">{{ __('Attendance logs will appear here once the sync from your biometric dtr.xlsx file is complete.') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-12">
                        {{ $records->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div x-show="printPreviewOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="printPreviewOpen = false"></div>
            <div class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#0038a8]">{{ __('UI Preview Only') }}</p>
                        <h2 class="text-xl font-black text-gray-900">{{ __('Printable DTR Copy') }}</h2>
                        <p class="text-xs text-gray-500 mt-1">{{ now()->startOfMonth()->format('M d, Y') }} - {{ now()->format('M d, Y') }}</p>
                    </div>
                    <button type="button" @click="printPreviewOpen = false" class="h-9 w-9 rounded-lg text-gray-400 hover:bg-gray-50 hover:text-gray-700">
                        <svg class="mx-auto w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Employee') }}</p>
                                <p class="font-black text-gray-900">{{ auth()->user()->employee?->firstname }} {{ auth()->user()->employee?->lastname }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Department') }}</p>
                                <p class="font-black text-gray-900">{{ auth()->user()->employee?->division ?? 'Sample Division' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Total Hours') }}</p>
                                <p class="font-black text-gray-900">156.00</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 overflow-hidden">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                <tr>
                                    <th class="px-5 py-3">{{ __('Date') }}</th>
                                    <th class="px-5 py-3">{{ __('Time In') }}</th>
                                    <th class="px-5 py-3">{{ __('Time Out') }}</th>
                                    <th class="px-5 py-3">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr><td class="px-5 py-4 font-bold">Jun 02, 2026</td><td class="px-5 py-4 font-bold">08:00 AM</td><td class="px-5 py-4 font-bold">05:00 PM</td><td class="px-5 py-4 font-bold text-emerald-600">Present</td></tr>
                                <tr><td class="px-5 py-4 font-bold">Jun 03, 2026</td><td class="px-5 py-4 font-bold">08:14 AM</td><td class="px-5 py-4 font-bold">05:05 PM</td><td class="px-5 py-4 font-bold text-amber-600">Late</td></tr>
                                <tr><td class="px-5 py-4 font-bold">Jun 04, 2026</td><td class="px-5 py-4 font-bold">08:01 AM</td><td class="px-5 py-4 font-bold">05:00 PM</td><td class="px-5 py-4 font-bold text-emerald-600">Present</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" disabled class="rounded-lg bg-gray-100 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-gray-400 cursor-not-allowed">{{ __('Download') }}</button>
                        <button type="button" disabled class="rounded-lg bg-gray-100 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-gray-400 cursor-not-allowed">{{ __('Print') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
