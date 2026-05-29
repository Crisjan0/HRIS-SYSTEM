<x-app-layout>
    <x-slot name="title">{{ __('My Leave Requests') }}</x-slot>

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

            <!-- Header Actions -->
            <div class="flex justify-end mb-6">
                <a href="{{ route('leaves.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-200 hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    File New Leave
                </a>
            </div>

            <div x-data="{ tab: 'credits' }">
                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="tab = 'applications'"
                                :class="tab === 'applications' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                            My Leave Applications
                        </button>
                        <button @click="tab = 'credits'"
                                :class="tab === 'credits' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase tracking-widest transition-colors duration-200">
                            My Leave Credits
                        </button>
                    </nav>
                </div>

                <!-- Credits Summary Tab -->
                <div x-show="tab === 'credits'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        @foreach($credits as $credit)
                            <div
                                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 ring-1 ring-black/5 transform hover:scale-105 transition-all duration-300">
                                <div class="text-[10px] font-black uppercase text-indigo-600 tracking-widest mb-1">
                                    {{ $credit->leaveType->name }}</div>
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
                                    <div class="{{ $color }} h-full transition-all duration-1000" style="width: {{ $percentage }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Leave Applications Tab -->
                <div x-show="tab === 'applications'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="space-y-4">
                        @forelse($leaves as $leaf)
                            <div
                                class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex flex-col md:flex-row md:items-center justify-between hover:shadow-md transition-shadow duration-300 gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between md:justify-start md:gap-4 mb-2 md:mb-1">
                                        <h3 class="text-lg font-bold text-gray-900 leading-tight">
                                            {{ $leaf->leaveType->name }}
                                        </h3>
                                        <div class="md:hidden">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'text-orange-500 bg-orange-50 border-orange-100',
                                                    'approved' => $leaf->is_paid ? 'text-green-600 bg-green-50 border-green-100' : 'text-indigo-600 bg-indigo-50 border-indigo-100',
                                                    'rejected' => 'text-red-600 bg-red-50 border-red-100',
                                                    'cancelled' => 'text-gray-500 bg-gray-50 border-gray-100',
                                                ];
                                                $colorClass = $statusColors[$leaf->status] ?? 'text-blue-500 bg-blue-50 border-blue-100';
                                            @endphp
                                            <span
                                                class="text-[10px] font-black uppercase tracking-widest {{ $colorClass }} px-2 py-0.5 rounded border">
                                                {{ $leaf->status_label }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-sm text-gray-600">
                                        <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($leaf->start_date)->format('M d, Y') }}</span>
                                        <span class="mx-1 text-gray-400">to</span>
                                        <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($leaf->end_date)->format('M d, Y') }}</span>
                                        <span class="ml-2 text-[10px] text-gray-400 uppercase tracking-widest">
                                            ({{ $leaf->duration }}
                                            {{ Str::plural('Day', $leaf->duration) }})
                                        </span>
                                    </div>

                                    <div class="mt-2 text-xs text-gray-500 italic">
                                        {{ $leaf->reason ?: 'No reason provided' }}
                                    </div>

                                    @if($leaf->remarks)
                                        <div
                                            class="mt-3 p-3 bg-indigo-50/30 rounded-lg text-xs text-indigo-700 italic border-l-2 border-indigo-200">
                                            <span class="font-bold uppercase tracking-tighter text-[9px] block not-italic mb-1">Admin
                                                Remarks:</span>
                                            "{{ $leaf->remarks }}"
                                        </div>
                                    @endif

                                    <div class="mt-3 flex items-center gap-4 text-[10px] text-gray-400 font-medium">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span>Filed on
                                                {{ \Carbon\Carbon::parse($leaf->date_filed)->format('M d, Y h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-center gap-4 border-t md:border-t-0 pt-4 md:pt-0">
                                    <div class="hidden md:block text-right">
                                        <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Status
                                        </div>
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest {{ $colorClass }} px-3 py-1 rounded-full border border-current opacity-80">
                                            {{ __($leaf->status) }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('leaves.show', $leaf) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:-translate-y-0.5">
                                            VIEW
                                        </a>
                                    </div>
                                </div>

                            </div>
                        @empty
                            <div class="bg-white rounded-xl border border-dashed border-gray-200 p-12 text-center">
                                <div class="text-gray-400 mb-2">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-lg font-medium text-gray-400 italic">
                                    {{ __('You haven\'t filed any leave requests yet.') }}</p>
                                <a href="{{ route('leaves.create') }}"
                                    class="mt-4 inline-flex items-center text-indigo-600 hover:underline font-bold text-sm">
                                    {{ __('File your first leave now') }}
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>