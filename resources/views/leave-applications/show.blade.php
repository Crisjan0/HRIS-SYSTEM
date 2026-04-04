<x-app-layout>
    <x-slot name="title">{{ __('Review Leave Application') }}</x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Details -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-8 md:p-10">
                        <!-- Employee Header -->
                        <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-50">
                            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl font-black">
                                {{ substr($leaveApplication->employee->firstname, 0, 1) }}{{ substr($leaveApplication->employee->lastname, 0, 1) }}
                            </div>
                            <div>
                                <h1 class="text-2xl font-black text-gray-900">{{ $leaveApplication->employee->firstname }} {{ $leaveApplication->employee->lastname }}</h1>
                                <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">{{ $leaveApplication->employee->role }}</p>
                            </div>
                        </div>

                        <!-- Leave Info -->
                        <div class="space-y-8">
                            <div class="flex flex-col items-center justify-center py-6 bg-gray-50/50 rounded-2xl border border-gray-100">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 mb-2">Requested Leave</span>
                                <h2 class="text-2xl font-black text-gray-800">{{ $leaveApplication->leaveType->name }}</h2>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">Schedule</span>
                                    <div class="text-sm font-bold text-gray-700">
                                        {{ \Carbon\Carbon::parse($leaveApplication->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($leaveApplication->end_date)->format('M d, Y') }}
                                    </div>
                                    @php $duration = \Carbon\Carbon::parse($leaveApplication->start_date)->diffInDays(\Carbon\Carbon::parse($leaveApplication->end_date)) + 1; @endphp
                                    <div class="text-[10px] font-black text-indigo-500 uppercase">Total of {{ $duration }} {{ Str::plural('Day', $duration) }}</div>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">Date Filed</span>
                                    <div class="text-sm font-bold text-gray-700">
                                        {{ \Carbon\Carbon::parse($leaveApplication->date_filed)->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                            </div>

                            <div class="relative pl-6 border-l-4 border-indigo-100 py-2">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">Reason for Leave</span>
                                <p class="text-gray-700 font-medium leading-relaxed italic">"{{ $leaveApplication->reason }}"</p>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Timeline -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-8">
                        <div class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-6 inline-block border-b-2 border-indigo-100 pb-1">Approval Progress</div>
                        <div class="space-y-4">
                            @php
                                $stages = [
                                    ['label' => 'Division Chief', 'status' => $leaveApplication->chief_status, 'approver' => $leaveApplication->chief, 'remarks' => $leaveApplication->chief_remarks],
                                    ['label' => 'HR Staff', 'status' => $leaveApplication->hrstaff_status, 'approver' => $leaveApplication->hrstaff, 'remarks' => $leaveApplication->hrstaff_remarks],
                                    ['label' => 'Regional Director', 'status' => $leaveApplication->rd_status, 'approver' => $leaveApplication->regionalDirector, 'remarks' => $leaveApplication->rd_remarks],
                                ];
                            @endphp

                            @foreach($stages as $stage)
                                <div class="flex items-start gap-4 p-5 rounded-2xl border {{ $stage['status'] === 'approved' ? 'bg-green-50/30 border-green-100' : ($stage['status'] === 'rejected' ? 'bg-red-50/30 border-red-100' : 'bg-gray-50/50 border-gray-100') }}">
                                    <div class="mt-1">
                                        @if($stage['status'] === 'approved')
                                            <div class="bg-green-500 p-1.5 rounded-full text-white">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        @elseif($stage['status'] === 'rejected')
                                            <div class="bg-red-500 p-1.5 rounded-full text-white">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                        @else
                                            <div class="bg-gray-300 p-1.5 rounded-full text-white">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="text-[10px] font-black uppercase tracking-wider text-gray-900">{{ $stage['label'] }}</div>
                                            <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded {{ $stage['status'] === 'approved' ? 'text-green-600 bg-green-50' : ($stage['status'] === 'rejected' ? 'text-red-600 bg-red-50' : 'text-gray-400 bg-gray-100') }}">
                                                {{ __($stage['status']) }}
                                            </span>
                                        </div>
                                        @if($stage['approver'])
                                            <div class="text-xs font-bold text-gray-700">{{ $stage['approver']->firstname }} {{ $stage['approver']->lastname }}</div>
                                        @endif
                                        @if($stage['remarks'])
                                            <div class="mt-2 text-xs text-gray-500 italic">"{{ $stage['remarks'] }}"</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
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
                        if ($isChief && $leaveApplication->chief_status === 'pending') {
                            $isMyTurn = true;
                        } elseif ($isHR && $leaveApplication->hrstaff_status === 'pending') {
                            if ($leaveApplication->chief_status === 'approved') {
                                $isMyTurn = true;
                            } else {
                                $waitingMessage = 'Waiting for Division Chief approval.';
                            }
                        } elseif ($isDirector && $leaveApplication->rd_status === 'pending') {
                            if ($leaveApplication->hrstaff_status === 'approved') {
                                $isMyTurn = true;
                            } else {
                                $waitingMessage = 'Waiting for HR Staff approval.';
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

                        <form action="{{ route('leave-applications.update', $leaveApplication->id) }}" method="POST" class="space-y-6">
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
