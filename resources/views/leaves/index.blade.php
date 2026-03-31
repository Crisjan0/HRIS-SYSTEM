<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Leave Requests') }}
            </h2>
            <a href="{{ route('leaves.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('File New Leave') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Credits Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                @foreach($credits as $credit)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 ring-1 ring-black/5 transform hover:scale-105 transition-all duration-300">
                        <div class="text-[10px] font-black uppercase text-indigo-600 tracking-widest mb-1">{{ $credit->leaveType->name }}</div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black text-gray-900">{{ number_format($credit->balance, 1) }}</span>
                            <span class="text-xs font-bold text-gray-400 capitalize">{{ __('Days Left') }}</span>
                        </div>
                        <div class="mt-4 w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                            @php
                                $total = $credit->leaveType->days_per_year ?? 15;
                                $percentage = $total > 0 ? ($credit->balance / $total) * 100 : 0;
                                $color = $percentage > 50 ? 'bg-indigo-600' : ($percentage > 20 ? 'bg-yellow-500' : 'bg-red-500');
                            @endphp
                            <div class="{{ $color }} h-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Leave Type') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Reason') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Date Filed') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Start Date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('End Date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-black text-gray-500 uppercase tracking-widest">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($leaves as $leaf)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $leaf->leaveType->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-500 italic max-w-sm overflow-hidden">{{ $leaf->reason }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium italic">{{ \Carbon\Carbon::parse($leaf->date_filed)->format('M d, Y h:i A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-700 font-black">{{ \Carbon\Carbon::parse($leaf->start_date)->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-700 font-black">{{ \Carbon\Carbon::parse($leaf->end_date)->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                    'approved' => 'bg-green-100 text-green-800 border-green-200',
                                                    'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                                    'cancelled' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                ];
                                                $currentClass = $statusClasses[$leaf->status] ?? 'bg-blue-100 text-blue-800 border-blue-200';
                                            @endphp
                                            <span class="px-2.5 py-0.5 inline-flex text-[10px] leading-5 font-black rounded-full border {{ $currentClass }} uppercase tracking-widest">
                                                {{ __($leaf->status) }}
                                            </span>
                                            @if($leaf->remarks)
                                                <div class="text-[10px] text-gray-400 mt-1 italic">{{ __('Remarks:') }} {{ $leaf->remarks }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            @if($leaf->status === 'pending')
                                                <a href="{{ route('leaves.edit', $leaf) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">{{ __('Edit') }}</a>
                                                <form action="{{ route('leaves.destroy', $leaf) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to cancel this leave request?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200">{{ __('Cancel') }}</button>
                                                </form>
                                            @else
                                                <span class="text-gray-300 italic text-xs">{{ __('No Actions') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <p class="text-lg font-medium text-gray-400 italic">{{ __('You haven\'t filed any leave requests yet.') }}</p>
                                                <a href="{{ route('leaves.create') }}" class="mt-4 text-indigo-600 hover:underline font-bold text-sm">
                                                    {{ __('File your first leave now') }}
                                                </a>
                                            </div>
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
