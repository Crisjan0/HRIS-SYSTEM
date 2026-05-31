<x-app-layout>
    <x-slot name="title">{{ __('My Travel Orders') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header Actions -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-black text-gray-900">{{ __('My Travel Orders') }}</h1>
                <a href="{{ route('travel-orders.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-200 hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Travel Order
                </a>
            </div>

            <div class="space-y-4">
                @forelse($travelOrders as $order)
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex flex-col md:flex-row md:items-center justify-between hover:shadow-md transition-shadow duration-300 gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900 leading-tight">
                                    {{ $order->places_of_travel }}
                                </h3>
                                @php
                                    $typeColors = [
                                        'local' => 'text-blue-600 bg-blue-50 border-blue-100',
                                        'foreign' => 'text-purple-600 bg-purple-50 border-purple-100',
                                        'official_business' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                    ];
                                    $typeColor = $typeColors[$order->travel_type] ?? 'text-gray-600 bg-gray-50 border-gray-100';
                                @endphp
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $typeColor }} px-2 py-0.5 rounded border">
                                    {{ $order->travel_type_label }}
                                </span>
                            </div>

                            <div class="text-sm text-gray-600">
                                <span class="font-medium">{{ $order->travel_date_start->format('M d, Y') }}</span>
                                <span class="mx-1 text-gray-400">to</span>
                                <span class="font-medium">{{ $order->travel_date_end->format('M d, Y') }}</span>
                            </div>

                            <div class="mt-2 text-xs text-gray-500 italic line-clamp-2">
                                {{ $order->purpose }}
                            </div>

                            @if($order->companions->count())
                                <div class="mt-2 flex items-center gap-2 text-[10px] text-gray-400 font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span>{{ $order->companions->count() }} {{ Str::plural('Companion', $order->companions->count()) }}</span>
                                </div>
                            @endif

                            <div class="mt-2 flex items-center gap-2 text-[10px] text-gray-400 font-medium">
                                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Created {{ $order->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>

                        <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-center gap-4 border-t md:border-t-0 pt-4 md:pt-0">
                            <div class="text-right">
                                <div class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Status</div>
                                @php
                                    $statusColors = [
                                        'pending' => 'text-orange-500 bg-orange-50 border-orange-100',
                                        'approved' => 'text-green-600 bg-green-50 border-green-100',
                                        'rejected' => 'text-red-600 bg-red-50 border-red-100',
                                    ];
                                    $statusColor = $statusColors[$order->status] ?? 'text-gray-500 bg-gray-50 border-gray-100';
                                @endphp
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $statusColor }} px-3 py-1 rounded-full border">
                                    {{ __($order->status) }}
                                </span>
                            </div>

                            <a href="{{ route('travel-orders.show', $order) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:-translate-y-0.5">
                                VIEW
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
                        <p class="text-lg font-medium text-gray-400 italic">
                            {{ __('You haven\'t created any travel orders yet.') }}
                        </p>
                        <a href="{{ route('travel-orders.create') }}" class="mt-4 inline-flex items-center text-indigo-600 hover:underline font-bold text-sm">
                            {{ __('Create your first travel order') }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
