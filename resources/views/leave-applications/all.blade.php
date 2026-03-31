<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave Records History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Employee') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Leave Type') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Duration') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Date Filed') }}</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($leaf->status === 'approved')
                                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-black uppercase tracking-widest">
                                                    {{ __('Approved') }}
                                                </span>
                                            @elseif($leaf->status === 'rejected')
                                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-black uppercase tracking-widest">
                                                    {{ __('Rejected') }}
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-[10px] font-black uppercase tracking-widest">
                                                    {{ __('Pending') }}
                                                </span>
                                            @endif
                                            
                                            @if($leaf->remarks)
                                                <div class="text-[10px] text-gray-400 mt-1 italic max-w-xs truncate" title="{{ $leaf->remarks }}">
                                                    "{{ $leaf->remarks }}"
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400 italic">
                                            {{ \Carbon\Carbon::parse($leaf->date_filed)->format('M d, Y h:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic font-medium">
                                            {{ __('No leave records found.') }}
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
