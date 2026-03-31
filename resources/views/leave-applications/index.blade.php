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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Employee') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Leave Type') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Duration') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Date Filed') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($leaves as $leaf)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-black text-gray-900">{{ $leaf->employee->firstname }} {{ $leaf->employee->lastname }}</div>
                                            <div class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $leaf->employee->role }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-indigo-600">{{ $leaf->leaveType->name }}</div>
                                            <div class="text-xs text-gray-500 italic max-w-xs truncate" title="{{ $leaf->reason }}">{{ $leaf->reason }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-black text-gray-900">
                                                @php
                                                    $duration = \Carbon\Carbon::parse($leaf->start_date)->diffInDays(\Carbon\Carbon::parse($leaf->end_date)) + 1;
                                                @endphp
                                                {{ $duration }} {{ Str::plural('Day', $duration) }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 font-medium">
                                                {{ \Carbon\Carbon::parse($leaf->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leaf->end_date)->format('M d, Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400 italic">
                                            {{ \Carbon\Carbon::parse($leaf->date_filed)->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-3" x-data="{ open: false, action: '' }">
                                                <button @click="open = true; action = 'approved'" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-md">
                                                    APPROVE
                                                </button>
                                                <button @click="open = true; action = 'rejected'" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-md">
                                                    REJECT
                                                </button>

                                                <!-- Simple Action Modal (Alpine.js) -->
                                                <template x-if="open">
                                                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                                                        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full text-left">
                                                            <h3 class="text-lg font-black text-gray-900 border-b pb-4 mb-6 uppercase tracking-widest" x-text="action === 'approved' ? 'Approve Leave Request' : 'Reject Leave Request'"></h3>
                                                            
                                                            <form action="{{ route('leave-applications.update', $leaf->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" :value="action">
                                                                
                                                                <div class="mb-6">
                                                                    <label class="block text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-2">{{ __('Remarks / Reason') }}</label>
                                                                    <textarea name="remarks" class="w-full border-gray-100 rounded-xl bg-gray-50/50 text-sm font-medium focus:ring-indigo-500 focus:border-indigo-500" rows="3" placeholder="Add some notes here..."></textarea>
                                                                </div>

                                                                <div class="flex justify-end gap-4 mt-8">
                                                                    <button type="button" @click="open = false" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 px-4 py-2">{{ __('Cancel') }}</button>
                                                                    <button type="submit" 
                                                                        :class="action === 'approved' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-red-600 hover:bg-red-700'" 
                                                                        class="px-8 py-3 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all transform hover:-translate-y-1 shadow-lg">
                                                                        CONFIRM ACTION
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic font-medium">
                                            {{ __('No pending leave applications found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
