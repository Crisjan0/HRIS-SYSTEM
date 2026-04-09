<x-app-layout>
    <x-slot name="title">{{ __('Leave Request Details') }}</x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-8 md:p-12">
                <!-- Status Header -->
                <div class="flex flex-col items-center mb-10">
                    @php
                        $statusColors = [
                            'pending' => 'text-orange-500 bg-orange-50 border-orange-100',
                            'approved' => $leaf->is_paid ? 'text-green-600 bg-green-50 border-green-100' : 'text-indigo-600 bg-indigo-50 border-indigo-100',
                            'rejected' => 'text-red-600 bg-red-50 border-red-100',
                            'cancelled' => 'text-gray-500 bg-gray-50 border-gray-100',
                        ];
                        $colorClass = $statusColors[$leaf->status] ?? 'text-blue-500 bg-blue-50 border-blue-100';
                    @endphp
                    <span class="text-[11px] font-black uppercase tracking-[0.2em] {{ $colorClass }} px-6 py-2 rounded-full border mb-4 text-center">
                        {{ $leaf->status_label }}
                    </span>
                    <h1 class="text-3xl font-black text-gray-900 text-center">{{ $leaf->leaveType->name }}</h1>
                    <p class="text-gray-400 mt-2 font-medium">Filed on {{ \Carbon\Carbon::parse($leaf->date_filed)->format('F d, Y \a\t h:i A') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <!-- Dates Card -->
                    <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100">
                        <div class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4 inline-block border-b-2 border-indigo-100 pb-1">{{ __('Schedule') }}</div>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-white p-2 rounded-lg shadow-sm">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ __('Start Date') }}</div>
                                    <div class="text-sm font-black text-gray-900">{{ \Carbon\Carbon::parse($leaf->start_date)->format('F d, Y') }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="bg-white p-2 rounded-lg shadow-sm">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ __('End Date') }}</div>
                                    <div class="text-sm font-black text-gray-900">{{ \Carbon\Carbon::parse($leaf->end_date)->format('F d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100 flex flex-col justify-center items-center">
                        <div class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4 inline-block border-b-2 border-indigo-100 pb-1">{{ __('Total Duration') }}</div>
                        <div class="flex items-baseline gap-1">
                            @php $duration = $leaf->duration; @endphp
                            <span class="text-5xl font-black text-gray-900">{{ $duration }}</span>
                            <span class="text-sm font-bold text-gray-400">{{ Str::plural('Day', $duration) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Approval Stages -->
                <div class="space-y-6">
                    <div class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4 inline-block border-b-2 border-indigo-100 pb-1">{{ __('Approval Progress') }}</div>
                    
                    <div class="grid grid-cols-1 gap-4">
                        @php
                            $stages = [
                                ['label' => 'Division Chief', 'status' => $leaf->chief_status, 'approver' => $leaf->chief, 'remarks' => $leaf->chief_remarks],
                                ['label' => 'HR Staff', 'status' => $leaf->hrstaff_status, 'approver' => $leaf->hrstaff, 'remarks' => $leaf->hrstaff_remarks],
                                ['label' => 'Regional Director', 'status' => $leaf->rd_status, 'approver' => $leaf->regionalDirector, 'remarks' => $leaf->rd_remarks],
                            ];
                        @endphp

                        @foreach($stages as $stage)
                            <div class="flex items-start gap-4 p-5 rounded-2xl border {{ $stage['status'] === 'approved' ? 'bg-green-50/30 border-green-100' : ($stage['status'] === 'rejected' ? 'bg-red-50/30 border-red-100' : 'bg-gray-50/50 border-gray-100') }}">
                                <div class="mt-1">
                                    @if($stage['status'] === 'approved')
                                        <div class="bg-green-500 p-1.5 rounded-full text-white">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    @elseif($stage['status'] === 'rejected')
                                        <div class="bg-red-500 p-1.5 rounded-full text-white">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                    @else
                                        <div class="bg-gray-300 p-1.5 rounded-full text-white animate-pulse">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="text-[11px] font-black uppercase tracking-wider text-gray-900">{{ $stage['label'] }}</div>
                                        <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded {{ $stage['status'] === 'approved' ? 'text-green-600' : ($stage['status'] === 'rejected' ? 'text-red-600' : 'text-gray-400') }}">
                                            {{ __($stage['status']) }}
                                        </span>
                                    </div>
                                    
                                    @if($stage['approver'])
                                        <div class="text-xs font-bold text-gray-700">{{ $stage['approver']->firstname }} {{ $stage['approver']->lastname }}</div>
                                    @endif

                                    @if($stage['remarks'])
                                        <div class="mt-2 text-xs text-gray-500 italic bg-white/50 p-2 rounded-lg border border-current/10">
                                            "{{ $stage['remarks'] }}"
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
