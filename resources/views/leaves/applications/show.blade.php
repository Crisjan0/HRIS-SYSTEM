<x-app-layout>
    <x-slot name="title">{{ __('Review Leave Application') }}</x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @php
                $backRoute = $leaveApplication->status === 'pending'
                    ? route('leave-applications.index')
                    : route('leave-applications.all');
                $backLabel = __('Back');
            @endphp

            <div class="mb-4 flex items-center justify-between gap-3">
                <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ $backLabel }}
                </a>
                <a href="{{ route('leave-applications.print', $leaveApplication) }}" target="_blank" data-no-transition class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">
                    {{ __('Print Leave Form') }}
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Details -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-8 md:p-10">
                        <!-- Employee Header -->
                        <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-50">
                            <x-profile-avatar :employee="$leaveApplication->employee" size="xl" variant="indigo" rounded="2xl" />
                            <div>
                                <h1 class="text-2xl font-black text-gray-900">{{ $leaveApplication->employee->firstname }} {{ $leaveApplication->employee->lastname }}</h1>
                                <div class="flex items-center gap-2">
                                    <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">
                                        {{ $leaveApplication->employee->position ?: __('No position') }}
                                        <span class="mx-1 text-gray-300">|</span>
                                        {{ $leaveApplication->employee->division ?: __('No division') }}
                                    </p>
                                    @if($leaveApplication->status !== 'pending')
                                        <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded border {{ $leaveApplication->is_paid ? 'text-green-600 bg-green-50 border-green-100' : 'text-indigo-600 bg-indigo-50 border-indigo-100' }}">
                                            {{ $leaveApplication->status_label }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Leave Info -->
                        <div class="space-y-8">
                            <div class="flex flex-col items-center justify-center py-6 bg-gray-50/50 rounded-2xl border border-gray-100">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 mb-2">Requested Leave</span>
                                <h2 class="text-2xl font-black text-gray-800">{{ $leaveApplication->leaveType->name }}</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">Schedule</span>
                                    <div class="text-xl font-bold text-gray-700">
                                        {{ \Carbon\Carbon::parse($leaveApplication->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($leaveApplication->end_date)->format('M d, Y') }}
                                    </div>
                                    <div class="text-[10px] font-black text-indigo-500 uppercase">Total of {{ $leaveApplication->duration }} {{ Str::plural('Day', $leaveApplication->duration) }}</div>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">Date Filed</span>
                                    <div class="text-xl font-bold text-gray-700">
                                        {{ \Carbon\Carbon::parse($leaveApplication->date_filed)->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">Credits Left</span>
                                    <div class="text-xl font-bold text-gray-700">
                                        {{ number_format($leaveCredit?->balance ?? 0, 1) }} {{ Str::plural('day', $leaveCredit?->balance ?? 0) }}
                                    </div>
                                    <div class="text-[10px] font-black text-indigo-500 uppercase">{{ $leaveApplication->leaveType->name }}</div>
                                </div>
                            </div>

                            <div class="relative pl-6 border-l-4 border-indigo-100 py-2">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Reason for Leave</span>
                                <p class="text-gray-700 font-medium leading-relaxed italic">"{{ $leaveApplication->reason }}"</p>
                            </div>

                            {{-- Attachment --}}
                            @if($leaveApplication->attachment_path)
                                <div class="mt-4">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-3">Attachment</span>
                                    <div class="flex items-center gap-4 p-4 bg-gray-50/50 rounded-2xl border border-gray-100">
                                        <div class="bg-indigo-100 p-3 rounded-xl">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ basename($leaveApplication->attachment_path) }}</p>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">{{ strtoupper(pathinfo($leaveApplication->attachment_path, PATHINFO_EXTENSION)) }} File</p>
                                        </div>
                                        <a href="{{ asset('storage/' . $leaveApplication->attachment_path) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:-translate-y-0.5 flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            View
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                @php
                    $role = strtolower(auth()->user()->role ?? '');
                    $isChief = $role === 'chief';
                    $isHR = in_array($role, ['hrstaff', 'hr staff', 'admin']);
                    $isDirector = in_array($role, ['regional director', 'regionaldirector', 'director']);

                    $isMyTurn = false;
                    $waitingMessage = '';

                    if ($leaveApplication->status === 'pending') {
                        if ($isHR && $leaveApplication->hrstaff_status === 'pending') {
                            $isMyTurn = true;
                        } elseif ($isChief && $leaveApplication->chief_status === 'pending') {
                            if (in_array($leaveApplication->hrstaff_status, ['approved', 'rejected'], true)) {
                                $isMyTurn = true;
                            } else {
                                $waitingMessage = 'Waiting for HR Admin verification.';
                            }
                        } elseif ($isDirector && $leaveApplication->rd_status === 'pending') {
                            if ($leaveApplication->chief_status === 'approved') {
                                $isMyTurn = true;
                            } else {
                                $waitingMessage = 'Waiting for Division Chief approval.';
                            }
                        }
                    }
                @endphp

                <!-- Right Column: Action Card -->
                <div class="space-y-8">
                    @if($isMyTurn)
                    <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-2xl shadow-indigo-200 sticky top-12 animate-in fade-in zoom-in duration-500">
                        <div class="mb-8">
                            <h3 class="text-xl font-black uppercase tracking-widest mb-2">Review Action</h3>
                            <p class="text-indigo-200 text-xs font-medium">Please provide your decision and remarks for this application.</p>
                        </div>

                        <form action="{{ route('leave-applications.update', $leaveApplication->id) }}" id="leaveStatusForm" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-2">{{ __('Remarks / Notes') }}</label>
                                <textarea name="remarks" class="w-full border-transparent rounded-2xl bg-indigo-500/50 text-sm font-medium focus:ring-white focus:border-white text-white placeholder-indigo-300 transition duration-200" rows="4" placeholder="Add some notes here..."></textarea>
                                <span id="leaveRemarksError" class="mt-2 hidden text-xs font-semibold text-red-200 block"></span>
                            </div>

                            <div class="space-y-3 pt-4">
                                <button type="submit" name="status" value="approved" class="w-full bg-white text-indigo-600 hover:bg-indigo-50 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg transform hover:-translate-y-1">
                                    {{ $isHR ? __('Verify Request') : __('Approve Request') }}
                                </button>
                                <button type="submit" name="status" value="rejected" class="w-full bg-indigo-500/50 border-2 border-indigo-400 text-white hover:bg-indigo-500 px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all">
                                    {{ __('Reject Request') }}
                                </button>
                            </div>
                        </form>
                        <script>
                            (function() {
                                const form = document.getElementById('leaveStatusForm');
                                const remarksField = form?.querySelector('textarea[name="remarks"]');
                                const errorSpan = document.getElementById('leaveRemarksError');

                                remarksField?.addEventListener('input', function() {
                                    remarksField.classList.remove('ring-2', 'ring-red-500', 'border-red-500');
                                    if (errorSpan) {
                                        errorSpan.classList.add('hidden');
                                        errorSpan.textContent = '';
                                    }
                                });

                                form?.addEventListener('submit', function(e) {
                                    const action = document.activeElement ? document.activeElement.getAttribute('value') : null;
                                    const remarks = remarksField.value.trim();
                                    const isHR = @js($isHR);

                                    if (action === 'rejected' && isHR && !remarks) {
                                        e.preventDefault();
                                        remarksField.classList.add('ring-2', 'ring-red-500', 'border-red-500');
                                        if (errorSpan) {
                                            errorSpan.textContent = 'Remarks are required when rejecting this application.';
                                            errorSpan.classList.remove('hidden');
                                        }
                                        remarksField.focus();
                                    }
                                });
                            })();
                        </script>
                    </div>
                    @endif
                    
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            @if($isMyTurn)
                                <div class="w-2 h-2 bg-orange-400 rounded-full animate-ping"></div>
                                <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Your Review Needed</span>
                            @elseif($waitingMessage)
                                <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                                <span class="text-[10px] font-black uppercase text-yellow-600 tracking-widest">In Progress</span>
                            @else
                                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                <span class="text-[10px] font-black uppercase text-green-500 tracking-widest">Application Processed</span>
                            @endif
                        </div>
                        <p class="text-[10px] text-gray-500 leading-relaxed font-medium">
                            @if($isMyTurn)
                                As the current reviewer, your decision will move this application to the next stage. Please verify all details before confirming.
                            @elseif($waitingMessage)
                                {{ $waitingMessage }} This application must be approved by the previous stage before you can take action.
                            @else
                                This leave request has already been finalized or is awaiting action in a different stage of the approval process.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

