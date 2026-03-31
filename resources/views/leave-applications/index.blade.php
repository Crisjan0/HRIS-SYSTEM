<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Leave Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse($leaves as $leaf)
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow duration-300">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 leading-tight">
                                {{ $leaf->employee->firstname }} {{ $leaf->employee->lastname }}
                            </h3>
                            <div class="text-sm text-gray-600 mt-1">
                                <span class="font-medium text-indigo-600">{{ $leaf->leaveType->name }}</span>
                                <span class="mx-1 text-gray-400">from</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($leaf->start_date)->format('M d, Y') }}</span>
                                <span class="mx-1 text-gray-400">to</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($leaf->end_date)->format('M d, Y') }}</span>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Status:</span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-orange-500 bg-orange-50 px-2 py-0.5 rounded">PENDING</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3" x-data="{ open: false, action: '' }">
                            <button @click="open = true; action = 'approved'" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all shadow-lg hover:-translate-y-0.5">
                                REVIEW
                            </button>

                            <!-- Simple Action Modal (Alpine.js) -->
                            <template x-if="open">
                                <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm">
                                    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full text-left" @click.away="open = false">
                                        <div class="flex items-center justify-between mb-6 border-b pb-4">
                                            <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest" 
                                                x-text="action === 'approved' ? 'Review Leave Request' : 'Review Leave Request'"></h3>
                                            <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                        
                                        <form action="{{ route('leave-applications.update', $leaf->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100">
                                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Details</div>
                                                <div class="text-sm font-bold text-gray-700">{{ $leaf->leaveType->name }}</div>
                                                <div class="text-xs text-gray-500 mt-1 italic italic italic">Reason: {{ $leaf->reason ?: 'No reason provided' }}</div>
                                            </div>

                                            <div class="mb-6">
                                                <label class="block text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-2">{{ __('Remarks / Reason') }}</label>
                                                <textarea name="remarks" class="w-full border-gray-200 rounded-xl bg-white text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500" rows="3" placeholder="Add some notes here..."></textarea>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4 mt-8">
                                                <button type="submit" name="status" value="rejected" class="w-full bg-white border-2 border-red-100 text-red-600 hover:bg-red-50 px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                                    REJECT
                                                </button>
                                                <button type="submit" name="status" value="approved" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-200">
                                                    APPROVE
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>
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
                            {{ __('No pending leave applications found.') }}
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
