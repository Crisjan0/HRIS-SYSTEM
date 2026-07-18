<x-app-layout>
    <x-slot name="title">{{ __('Leave Request Details') }}</x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-5 flex items-center justify-between gap-3">
                <a href="{{ route('leaves.index') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-indigo-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    {{ __('Back') }}
                </a>
                <a href="{{ route('leaves.print', $leaf) }}" target="_blank" data-no-transition class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">
                    {{ __('Print Leave Form') }}
                </a>
            </div>
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

                @if($leaf->leaveType->description || $leaf->leaveType->legal_basis)
                    <div class="mb-10 p-5 bg-indigo-50/50 border border-indigo-100 rounded-2xl">
                        @if($leaf->leaveType->description)
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $leaf->leaveType->description }}</p>
                        @endif
                        @if($leaf->leaveType->legal_basis)
                            <p class="mt-2 text-xs font-semibold text-indigo-600/80 italic">
                                <span class="font-black uppercase tracking-wider text-[10px] text-indigo-500 not-italic">{{ __('Legal Basis') }}:</span>
                                {{ $leaf->leaveType->legal_basis }}
                            </p>
                        @endif
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
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

                    <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100 flex flex-col justify-center items-center">
                        <div class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4 inline-block border-b-2 border-indigo-100 pb-1">{{ __('Credits Left') }}</div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-black text-gray-900">{{ number_format($leaveCredit?->balance ?? 0, 1) }}</span>
                            <span class="text-sm font-bold text-gray-400">{{ Str::plural('Day', $leaveCredit?->balance ?? 0) }}</span>
                        </div>
                        <p class="mt-2 text-center text-[10px] font-black uppercase tracking-widest text-gray-400">{{ $leaf->leaveType->name }}</p>
                    </div>
                </div>

                <!-- Approval Stages -->
                <div class="space-y-6">
                    <div class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-5 inline-block border-b-2 border-indigo-100 pb-1">{{ __('Approval Progress') }}</div>
                    
                    <div class="grid grid-cols-1 gap-4">
                        @php
                            $stages = [
                                ['label' => 'Division Chief', 'status' => $leaf->chief_status, 'approver' => $leaf->chief, 'remarks' => $leaf->chief_remarks],
                                ['label' => 'HR Staff', 'status' => $leaf->hrstaff_status, 'approver' => $leaf->hrstaff, 'remarks' => $leaf->hrstaff_remarks],
                                ['label' => 'Regional Director', 'status' => $leaf->rd_status, 'approver' => $leaf->regionalDirector, 'remarks' => $leaf->rd_remarks],
                            ];
                        @endphp

                        @foreach($stages as $stage)
                            <div class="flex items-start gap-5 p-6 rounded-2xl border {{ $stage['status'] === 'approved' ? 'bg-green-50/30 border-green-100' : ($stage['status'] === 'rejected' ? 'bg-red-50/30 border-red-100' : 'bg-gray-50/50 border-gray-100') }}">
                                <div class="mt-1">
                                    @if($stage['status'] === 'approved')
                                        <div class="bg-green-500 p-2 rounded-full text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    @elseif($stage['status'] === 'rejected')
                                        <div class="bg-red-500 p-2 rounded-full text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                    @else
                                        <div class="bg-gray-300 p-2 rounded-full text-white animate-pulse">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                            <div class="text-sm font-black uppercase tracking-wider text-gray-900">{{ $stage['label'] }}</div>
                                        <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded {{ $stage['status'] === 'approved' ? 'text-green-600' : ($stage['status'] === 'rejected' ? 'text-red-600' : 'text-gray-400') }}">
                                            {{ __($stage['status']) }}
                                        </span>
                                    </div>
                                    
                                    @if($stage['approver'])
                                        <div class="text-sm font-bold text-gray-700">{{ $stage['approver']->firstname }} {{ $stage['approver']->lastname }}</div>
                                    @endif

                                    @if($stage['remarks'])
                                        <div class="mt-3 text-sm text-gray-500 italic bg-white/50 p-3 rounded-lg border border-current/10">
                                            "{{ $stage['remarks'] }}"
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Attachment --}}
                    @if($leaf->attachment_path)
                        <div class="mt-10">
                            <div class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4 inline-block border-b-2 border-indigo-100 pb-1">{{ __('Attachment') }}</div>
                            <div class="flex items-center gap-4 p-4 bg-gray-50/50 rounded-2xl border border-gray-100">
                                <div class="bg-indigo-100 p-3 rounded-xl">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ basename($leaf->attachment_path) }}</p>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">{{ strtoupper(pathinfo($leaf->attachment_path, PATHINFO_EXTENSION)) }} File</p>
                                </div>
                                <a href="{{ asset('storage/' . $leaf->attachment_path) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    View
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

