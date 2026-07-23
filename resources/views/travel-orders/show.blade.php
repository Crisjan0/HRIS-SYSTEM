<x-app-layout>
    <x-slot name="title">{{ __('Travel Authority Details') }}</x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @php
                $role = strtolower(auth()->user()->role ?? '');
                $isApprover = in_array($role, ['admin', 'hrstaff', 'chief', 'regionaldirector']);
            @endphp

            <div class="mb-5 flex items-center justify-between gap-3">
                <a href="{{ $isApprover ? route('hr.travel-orders.index') : route('travel-orders.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-blue-900 shadow-sm transition hover:border-blue-200 hover:text-blue-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back') }}
                </a>
                <a href="{{ route('travel-orders.print', $travelOrder) }}" target="_blank" rel="noopener" data-no-transition class="inline-flex items-center gap-2 rounded-xl bg-blue-900 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-blue-800">
                    <i class="fa-solid fa-print"></i>
                    {{ __('Print Travel Authority') }}
                </a>
            </div>

            @php
                $trackedEmployee = trim(($travelOrder->employee?->firstname ?? '') . ' ' . ($travelOrder->employee?->lastname ?? ''));
                $trackedOrderPayload = [
                    'id' => (string) $travelOrder->id,
                    'title' => $isApprover ? $trackedEmployee : (string) $travelOrder->places_of_travel,
                    'employee' => $trackedEmployee,
                    'type' => (string) $travelOrder->places_of_travel,
                    'stages' => [
                        ['label' => 'HR', 'status' => $travelOrder->hrstaff_status ?: 'pending'],
                        ['label' => 'Chief', 'status' => $travelOrder->chief_status ?: 'pending'],
                        ['label' => 'Regional Director', 'status' => $travelOrder->rd_status ?: 'pending'],
                    ],
                ];
            @endphp
            <x-approval-tracker :payload="$trackedOrderPayload" event="travel-selected" empty="No travel authority to track yet." />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column: Details --}}
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-8 md:p-10">
                        {{-- Employee Header --}}
                        <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-50">
                            <x-profile-avatar :employee="$travelOrder->employee" size="xl" variant="indigo" rounded="2xl" />
                            <div>
                                <h1 class="text-2xl font-black text-gray-900">{{ $travelOrder->employee->firstname }} {{ $travelOrder->employee->lastname }}</h1>
                                <div class="flex items-center gap-2">
                                    <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">
                                        {{ $travelOrder->employee->position ?: __('No position') }}
                                        <span class="mx-1 text-gray-300">|</span>
                                        {{ $travelOrder->employee->division ?: __('No division') }}
                                    </p>
                                    @php
                                        $typeColors = [
                                            'local' => 'text-blue-600 bg-blue-50 border-blue-100',
                                            'foreign' => 'text-purple-600 bg-purple-50 border-purple-100',
                                            'official_business' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                        ];
                                        $typeColor = $typeColors[$travelOrder->travel_type] ?? 'text-gray-600 bg-gray-50 border-gray-100';
                                    @endphp
                                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded border {{ $typeColor }}">
                                        {{ $travelOrder->travel_type_label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Travel Info --}}
                        <div class="space-y-8">
                            <div class="flex flex-col items-center justify-center py-6 bg-gray-50/50 rounded-2xl border border-gray-100">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 mb-2">Destination</span>
                                <h2 class="text-2xl font-black text-gray-800">{{ $travelOrder->places_of_travel }}</h2>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">Schedule</span>
                                    <div class="text-xl font-bold text-gray-700">
                                        {{ $travelOrder->travel_date_start->format('M d, Y') }} - {{ $travelOrder->travel_date_end->format('M d, Y') }}
                                    </div>
                                    @php $days = $travelOrder->travel_date_start->diffInDays($travelOrder->travel_date_end) + 1; @endphp
                                    <div class="text-[10px] font-black text-indigo-500 uppercase">Total of {{ $days }} {{ Str::plural('Day', $days) }}</div>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">Date Created</span>
                                    <div class="text-xl font-bold text-gray-700">
                                        {{ $travelOrder->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                            </div>

                            <div class="relative pl-6 border-l-4 border-indigo-100 py-2">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Purpose of Travel</span>
                                <p class="text-gray-700 font-medium leading-relaxed italic">"{{ $travelOrder->purpose }}"</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="rounded-2xl border border-gray-100 bg-gray-50/50 p-4">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Requesting Office</span>
                                    <p class="text-sm font-bold text-gray-700">{{ $travelOrder->requesting_office ?: 'Regional Office XI' }}</p>
                                </div>
                                <div class="rounded-2xl border border-gray-100 bg-gray-50/50 p-4">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Vehicle / Plate No.</span>
                                    <p class="text-sm font-bold text-gray-700">{{ $travelOrder->vehicle_plate_no ?: 'N/A' }}</p>
                                </div>
                                <div class="rounded-2xl border border-gray-100 bg-gray-50/50 p-4">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Name of Driver</span>
                                    <p class="text-sm font-bold text-gray-700">{{ $travelOrder->driver_name ?: 'N/A' }}</p>
                                </div>
                                <div class="rounded-2xl border border-gray-100 bg-gray-50/50 p-4">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Notes / Remarks</span>
                                    <p class="text-sm font-bold text-gray-700">{{ $travelOrder->notes_remarks ?: 'N/A' }}</p>
                                </div>
                            </div>

                            @if($travelOrder->attachment_path)
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-3">Attached File</span>
                                    <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-xl border border-gray-100">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-gray-900 truncate">{{ basename($travelOrder->attachment_path) }}</p>
                                                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">{{ strtoupper(pathinfo($travelOrder->attachment_path, PATHINFO_EXTENSION)) }} File</p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $travelOrder->attachment_path) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:-translate-y-0.5 flex items-center gap-2 shrink-0">
                                            View
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Companions --}}
                            @if($travelOrder->companions->count())
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-3">Employee(s)</span>
                                    <div class="space-y-2">
                                        @foreach($travelOrder->companions as $companion)
                                            <div class="flex items-center gap-3 p-3 bg-gray-50/50 rounded-xl border border-gray-100">
                                                <x-profile-avatar :employee="$companion" size="sm" variant="indigo" rounded="2xl" />
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $companion->firstname }} {{ $companion->lastname }}</div>
                                                    @if($companion->position)
                                                        <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">{{ $companion->position }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Right Column: Action Card --}}
                @php
                    $isDirector = $role === 'regionaldirector';

                    $isMyTurn = false;
                    $waitingMessage = '';

                    if ($travelOrder->status === 'pending') {
                        if ($isDirector && $travelOrder->rd_status === 'pending') {
                            $isMyTurn = true;
                        } elseif (! $isDirector) {
                            $waitingMessage = 'Waiting for Regional Director approval.';
                        }
                    }
                @endphp

                <div class="space-y-8">
                    @if($isMyTurn)
                    <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-2xl shadow-indigo-200 sticky top-12">
                        <div class="mb-8">
                            <h3 class="text-xl font-black uppercase tracking-widest mb-2">Review Action</h3>
                            <p class="text-indigo-200 text-xs font-medium">Please provide your decision and remarks for this travel authority.</p>
                        </div>

                        <form action="{{ route('travel-orders.update-status', $travelOrder->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-2">{{ __('Remarks / Notes') }}</label>
                                <textarea name="remarks" class="w-full border-transparent rounded-2xl bg-indigo-500/50 text-sm font-medium focus:ring-white focus:border-white text-white placeholder-indigo-300" rows="4" placeholder="Add some notes here..."></textarea>
                            </div>

                            <div class="space-y-3 pt-4">
                                <button type="submit" name="status" value="approved" class="w-full bg-white text-indigo-600 hover:bg-indigo-50 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg transform hover:-translate-y-1">
                                    {{ __('Approve Travel Authority') }}
                                </button>
                                <button type="submit" name="status" value="rejected" class="w-full bg-indigo-500/50 border-2 border-indigo-400 text-white hover:bg-indigo-500 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all">
                                    {{ __('Reject Travel Authority') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            @if($isMyTurn)
                                <div class="w-2 h-2 bg-orange-400 rounded-full animate-ping"></div>
                                <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Your Review Needed</span>
                            @elseif($waitingMessage)
                                <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                                <span class="text-[10px] font-black uppercase text-yellow-600 tracking-widest">In Progress</span>
                            @elseif($travelOrder->status === 'approved')
                                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                <span class="text-[10px] font-black uppercase text-green-500 tracking-widest">Fully Approved</span>
                            @elseif($travelOrder->status === 'rejected')
                                <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                                <span class="text-[10px] font-black uppercase text-red-500 tracking-widest">Rejected</span>
                            @else
                                <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                                <span class="text-[10px] font-black uppercase text-orange-500 tracking-widest">Pending Review</span>
                            @endif
                        </div>
                        <p class="text-[10px] text-gray-500 leading-relaxed font-medium">
                            @if($isMyTurn)
                                As the current reviewer, your decision will finalize this travel authority. Please verify all details before confirming.
                            @elseif($waitingMessage)
                                {{ $waitingMessage }}
                            @elseif($travelOrder->status === 'approved')
                                This travel authority has been fully approved by all required approvers.
                            @elseif($travelOrder->status === 'rejected')
                                This travel authority has been rejected.
                            @else
                                This travel authority is awaiting review from the Regional Director.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

