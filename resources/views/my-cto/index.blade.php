<x-app-layout>
    <x-slot name="title">{{ __('My Compensatory Time-Off') }}</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-black text-gray-900">{{ __('My Compensatory Time-Off') }}</h1>
                <a href="{{ route('my-cto.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-200 hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('File CTO Request') }}
                </a>
            </div>

            {{-- Balance Card --}}
            <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-xl shadow-indigo-200 md:col-span-1">
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-200">Available Balance</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-4xl font-black">{{ number_format($employee->cto_balance, 1) }}</span>
                        <span class="text-sm font-bold text-indigo-200 uppercase">Hours</span>
                    </div>
                    <p class="mt-2 text-xs text-indigo-200">Compensatory time-off credits available to use</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm md:col-span-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Quick Guide</span>
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-start gap-3">
                            <span class="shrink-0 w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-black">+</span>
                            <div>
                                <p class="font-bold text-gray-900">Earn CTO</p>
                                <p class="text-xs text-gray-500 mt-0.5">File overtime or extra work rendered to earn credits.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="shrink-0 w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">−</span>
                            <div>
                                <p class="font-bold text-gray-900">Use CTO</p>
                                <p class="text-xs text-gray-500 mt-0.5">Apply to use earned credits as time off.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($ctoRequests as $request)
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex flex-col md:flex-row md:items-center justify-between hover:shadow-md transition-shadow duration-300 gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900 leading-tight">
                                    {{ $request->type_label }}
                                </h3>
                                @php
                                    $typeColors = [
                                        'earn' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                        'use' => 'text-blue-600 bg-blue-50 border-blue-100',
                                    ];
                                    $typeColor = $typeColors[$request->type] ?? 'text-gray-600 bg-gray-50 border-gray-100';
                                @endphp
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $typeColor }} px-2 py-0.5 rounded border">
                                    {{ number_format($request->hours, 1) }} {{ Str::plural('Hour', $request->hours) }}
                                </span>
                            </div>

                            <div class="text-sm text-gray-600">
                                <span class="font-medium">{{ $request->date_start->format('M d, Y') }}</span>
                                <span class="mx-1 text-gray-400">to</span>
                                <span class="font-medium">{{ $request->date_end->format('M d, Y') }}</span>
                            </div>

                            <div class="mt-2 text-xs text-gray-500 italic line-clamp-2">
                                {{ $request->purpose }}
                            </div>

                            <div class="mt-2 flex items-center gap-2 text-[10px] text-gray-400 font-medium">
                                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Filed {{ $request->created_at->format('M d, Y h:i A') }}</span>
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
                                    $statusColor = $statusColors[$request->status] ?? 'text-gray-500 bg-gray-50 border-gray-100';
                                @endphp
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $statusColor }} px-3 py-1 rounded-full border">
                                    {{ __($request->status) }}
                                </span>
                            </div>

                            <a href="{{ route('my-cto.show', $request) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:-translate-y-0.5">
                                VIEW
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-dashed border-gray-200 p-12 text-center">
                        <div class="text-gray-400 mb-2">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-lg font-medium text-gray-400 italic">
                            {{ __('No compensatory time-off records found.') }}
                        </p>
                        <a href="{{ route('my-cto.create') }}" class="mt-4 inline-flex items-center text-indigo-600 hover:underline font-bold text-sm">
                            {{ __('File your first CTO request') }}
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
