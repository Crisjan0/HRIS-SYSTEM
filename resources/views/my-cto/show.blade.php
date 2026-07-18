<x-app-layout>
    <x-slot name="title">{{ __('CTO Request Details') }}</x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @php
                $role = strtolower(auth()->user()->role ?? '');
                $isApprover = in_array($role, ['admin', 'hrstaff', 'hr staff', 'chief', 'director', 'regionaldirector', 'regional director']);
            @endphp

            <div class="mb-5 flex items-center justify-between gap-3">
                <a href="{{ $isApprover ? route('hr.cto.index') : route('my-cto.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-blue-900 shadow-sm transition hover:border-blue-200 hover:text-blue-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back') }}
                </a>
                <a href="{{ route('my-cto.print', $ctoRequest) }}" target="_blank" data-no-transition class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-700">
                    {{ __('Print CTO') }}
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-8 md:p-10">
                        <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-50">
                            <x-profile-avatar :employee="$ctoRequest->employee" size="xl" variant="indigo" rounded="2xl" />
                            <div>
                                <h1 class="text-2xl font-black text-gray-900">{{ $ctoRequest->employee->firstname }} {{ $ctoRequest->employee->lastname }}</h1>
                                <div class="flex items-center gap-2">
                                    @if($ctoRequest->employee->position)
                                        <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">{{ $ctoRequest->employee->position }}</p>
                                    @endif
                                    @php
                                        $typeColors = [
                                            'earn' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                            'use' => 'text-blue-600 bg-blue-50 border-blue-100',
                                        ];
                                        $typeColor = $typeColors[$ctoRequest->type] ?? 'text-gray-600 bg-gray-50 border-gray-100';
                                    @endphp
                                    <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded border {{ $typeColor }}">
                                        {{ $ctoRequest->type_label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="flex flex-col items-center justify-center py-6 bg-gray-50/50 rounded-2xl border border-gray-100">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 mb-2">Hours Requested</span>
                                <h2 class="text-2xl font-black text-gray-800">{{ number_format($ctoRequest->hours, 1) }} {{ Str::plural('Hour', $ctoRequest->hours) }}</h2>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">
                                        {{ $ctoRequest->type === 'earn' ? 'Work Period' : 'CTO Period' }}
                                    </span>
                                    <div class="text-xl font-bold text-gray-700">
                                        {{ $ctoRequest->date_start->format('M d, Y') }} - {{ $ctoRequest->date_end->format('M d, Y') }}
                                    </div>
                                    @php $days = $ctoRequest->date_start->diffInDays($ctoRequest->date_end) + 1; @endphp
                                    <div class="text-[10px] font-black text-indigo-500 uppercase">Total of {{ $days }} {{ Str::plural('Day', $days) }}</div>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">Date Filed</span>
                                    <div class="text-xl font-bold text-gray-700">
                                        {{ $ctoRequest->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                            </div>

                            <div class="relative pl-6 border-l-4 border-indigo-100 py-2">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">
                                    {{ $ctoRequest->type === 'earn' ? 'Description of Work Rendered' : 'Purpose / Reason' }}
                                </span>
                                <p class="text-gray-700 font-medium leading-relaxed italic">"{{ $ctoRequest->purpose }}"</p>
                            </div>

                            @if($ctoRequest->attachment_path)
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
                                                <p class="text-sm font-bold text-gray-900 truncate">{{ basename($ctoRequest->attachment_path) }}</p>
                                                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">{{ strtoupper(pathinfo($ctoRequest->attachment_path, PATHINFO_EXTENSION)) }} File</p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $ctoRequest->attachment_path) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:-translate-y-0.5 flex items-center gap-2 shrink-0">
                                            View
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($ctoRequest->applicant_signature_path)
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-3">Applicant Signature</span>
                                    <div class="flex items-center justify-center rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                                        <img src="{{ asset('storage/' . $ctoRequest->applicant_signature_path) }}" alt="Applicant signature" class="max-h-20 object-contain">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-8">
                        <div class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-6 inline-block border-b-2 border-indigo-100 pb-1">Approval Progress</div>
                        <div class="space-y-5">
                            @php
                                $stages = [
                                    ['label' => 'Division Chief', 'status' => $ctoRequest->chief_status, 'approver' => $ctoRequest->chief, 'remarks' => $ctoRequest->chief_remarks],
                                    ['label' => 'HR Staff', 'status' => $ctoRequest->hrstaff_status, 'approver' => $ctoRequest->hrstaff, 'remarks' => $ctoRequest->hrstaff_remarks],
                                    ['label' => 'Regional Director', 'status' => $ctoRequest->rd_status, 'approver' => $ctoRequest->regionalDirector, 'remarks' => $ctoRequest->rd_remarks],
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
                                            <div class="bg-gray-300 p-2 rounded-full text-white">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="text-sm font-black uppercase tracking-wider text-gray-900">{{ $stage['label'] }}</div>
                                            <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded {{ $stage['status'] === 'approved' ? 'text-green-600 bg-green-50' : ($stage['status'] === 'rejected' ? 'text-red-600 bg-red-50' : 'text-gray-400 bg-gray-100') }}">
                                                {{ __($stage['status']) }}
                                            </span>
                                        </div>
                                        @if($stage['approver'])
                                            <div class="text-sm font-bold text-gray-700">{{ $stage['approver']->firstname }} {{ $stage['approver']->lastname }}</div>
                                        @endif
                                        @if($stage['remarks'])
                                            <div class="mt-3 text-sm text-gray-500 italic">"{{ $stage['remarks'] }}"</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @php
                    $isChief = $role === 'chief';
                    $isHR = in_array($role, ['hrstaff', 'hr staff', 'admin']);
                    $isRegionalDirector = in_array($role, ['director', 'regionaldirector', 'regional director']);

                    $isMyTurn = false;
                    $waitingMessage = '';

                    if ($ctoRequest->status === 'pending') {
                        if ($isChief && $ctoRequest->chief_status === 'pending') {
                            $isMyTurn = true;
                        } elseif ($isHR && $ctoRequest->hrstaff_status === 'pending') {
                            if ($ctoRequest->chief_status === 'approved') {
                                $isMyTurn = true;
                            } else {
                                $waitingMessage = 'Waiting for Division Chief approval.';
                            }
                        } elseif ($isRegionalDirector && $ctoRequest->rd_status === 'pending') {
                            if ($ctoRequest->hrstaff_status === 'approved') {
                                $isMyTurn = true;
                            } else {
                                $waitingMessage = 'Waiting for HR Staff approval.';
                            }
                        }
                    }
                @endphp

                <div class="space-y-8">
                    @if($isApprover && $ctoRequest->attachment_path)
                        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-3">{{ __('Attached File') }}</span>
                            <a href="{{ asset('storage/' . $ctoRequest->attachment_path) }}" target="_blank" class="mx-auto h-10 w-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-all shadow-md hover:-translate-y-0.5 flex items-center justify-center" title="{{ __('View Attachment') }}" aria-label="{{ __('View Attachment') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span class="sr-only">{{ __('View Attachment') }}</span>
                            </a>
                        </div>
                    @endif

                    @if($isMyTurn)
                    <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-2xl shadow-indigo-200 sticky top-12">
                        <div class="mb-8">
                            <h3 class="text-xl font-black uppercase tracking-widest mb-2">Review Action</h3>
                            <p class="text-indigo-200 text-xs font-medium">Please provide your decision and remarks for this CTO request.</p>
                        </div>

                        <form action="{{ route('cto.update-status', $ctoRequest->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-2">{{ __('Remarks / Notes') }}</label>
                                <textarea name="remarks" class="w-full border-transparent rounded-2xl bg-indigo-500/50 text-sm font-medium focus:ring-white focus:border-white text-white placeholder-indigo-300" rows="4" placeholder="Add some notes here..."></textarea>
                            </div>

                            <div class="space-y-3 pt-4">
                                <button type="submit" name="status" value="approved" class="w-full bg-white text-indigo-600 hover:bg-indigo-50 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg transform hover:-translate-y-1">
                                    {{ __('Approve Request') }}
                                </button>
                                <button type="submit" name="status" value="rejected" class="w-full bg-indigo-500/50 border-2 border-indigo-400 text-white hover:bg-indigo-500 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all">
                                    {{ __('Reject Request') }}
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
                            @elseif($ctoRequest->status === 'approved')
                                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                <span class="text-[10px] font-black uppercase text-green-500 tracking-widest">Fully Approved</span>
                            @elseif($ctoRequest->status === 'rejected')
                                <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                                <span class="text-[10px] font-black uppercase text-red-500 tracking-widest">Rejected</span>
                            @else
                                <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                                <span class="text-[10px] font-black uppercase text-orange-500 tracking-widest">Pending Review</span>
                            @endif
                        </div>
                        <p class="text-[10px] text-gray-500 leading-relaxed font-medium">
                            @if($isMyTurn)
                                As the current reviewer, your decision will move this CTO request to the next stage.
                            @elseif($waitingMessage)
                                {{ $waitingMessage }}
                            @elseif($ctoRequest->status === 'approved')
                                This CTO request has been fully approved.
                            @elseif($ctoRequest->status === 'rejected')
                                This CTO request has been rejected.
                            @else
                                This CTO request is awaiting the next reviewer.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

