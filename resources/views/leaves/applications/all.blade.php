<x-app-layout>
    <x-slot name="title">{{ __('Leave Records History') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('leaves._manage-tabs')

            <div class="space-y-4">
                @forelse($leaves as $leaf)
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex flex-col md:flex-row md:items-center justify-between hover:shadow-md transition-shadow duration-300 gap-4">
                        <div class="flex-1">
                            <div class="flex items-center justify-between md:justify-start md:gap-4 mb-2 md:mb-1">
                                <h3 class="text-lg font-bold text-gray-900 leading-tight">
                                    {{ $leaf->employee->firstname }} {{ $leaf->employee->lastname }}
                                </h3>
                                <div class="md:hidden">
                                    @if($leaf->status === 'approved')
                                        <span class="text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded border {{ $leaf->is_paid ? 'text-green-600 bg-green-50 border-green-100' : 'text-blue-900 bg-blue-800 border-blue-900' }}">
                                            {{ $leaf->status_label }}
                                        </span>
                                    @elseif($leaf->status === 'rejected')
                                        <span class="text-[10px] font-black uppercase tracking-widest text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100">REJECTED</span>
                                    @else
                                        <span class="text-[10px] font-black uppercase tracking-widest text-orange-500 bg-orange-50 px-2 py-0.5 rounded border border-orange-100">PENDING</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium text-indigo-600">{{ $leaf->leaveType->name }}</span>
                                <span class="mx-1 text-gray-400">from</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($leaf->start_date)->format('M d, Y') }}</span>
                                <span class="mx-1 text-gray-400">to</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($leaf->end_date)->format('M d, Y') }}</span>
                            </div>
                            
                            @if($leaf->remarks)
                                <div class="mt-2 p-3 bg-gray-50 rounded-lg text-xs text-gray-500 italic border-l-2 border-gray-200">
                                    "{{ $leaf->remarks }}"
                                </div>
                            @endif

                            <div class="mt-3 flex items-center gap-4 text-[10px] text-gray-400 font-medium">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>Filed on {{ \Carbon\Carbon::parse($leaf->date_filed)->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="hidden md:flex flex-col items-end justify-center gap-3 border-l pl-8 border-gray-50">
                            <div class="text-right">
                                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Status</div>
                                @if($leaf->status === 'approved')
                                    <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border {{ $leaf->is_paid ? 'text-green-600 bg-green-50 border-green-100' : 'text-indigo-600 bg-indigo-50 border-indigo-100' }}">
                                        {{ $leaf->status_label }}
                                    </span>
                                @elseif($leaf->status === 'rejected')
                                    <span class="text-[10px] font-black uppercase tracking-widest text-red-600 bg-red-50 px-3 py-1 rounded-full border border-red-100">REJECTED</span>
                                @else
                                    <span class="text-[10px] font-black uppercase tracking-widest text-orange-500 bg-orange-50 px-3 py-1 rounded-full border border-orange-100">PENDING</span>
                                @endif
                            </div>
                            <a href="{{ route('leave-applications.show', $leaf->id) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:-translate-y-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ __('View') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-dashed border-gray-200 p-12 text-center">
                        <div class="text-gray-400 mb-2">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 italic font-medium">
                            {{ __('No leave records found.') }}
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
