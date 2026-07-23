<x-app-layout>
    <x-slot name="title">{{ __('Locator Slip Details') }}</x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-5 flex items-center justify-between gap-3">
                <a href="{{ route('locator-slips.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-blue-900 shadow-sm transition hover:border-blue-200 hover:text-blue-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back') }}
                </a>

                <div class="flex items-center gap-2">
                    <a href="{{ route('locator-slips.print', $locatorSlip) }}" target="_blank" data-no-transition class="inline-flex h-10 items-center justify-center rounded-xl bg-indigo-600 px-4 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-700">
                        {{ __('Print') }}
                    </a>
                    @if(strtolower($locatorSlip->status) === 'pending')
                        <a href="{{ route('locator-slips.edit', $locatorSlip) }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-gray-200 bg-white px-4 text-xs font-black uppercase tracking-widest text-gray-700 shadow-sm transition hover:border-indigo-200 hover:text-indigo-700">
                            {{ __('Edit') }}
                        </a>
                    @endif
                </div>
            </div>

            @php
                $displayStatus = strtolower($locatorSlip->status) === 'approved by chief' ? 'approved' : strtolower($locatorSlip->status);
                $trackedLocatorPayload = [
                    'id' => (string) $locatorSlip->id,
                    'title' => ($locatorSlip->type ?? '') === 'Personal' ? 'Pass Slip' : (string) ($locatorSlip->type ?? 'Locator Slip'),
                    'type' => ($locatorSlip->type ?? '') === 'Personal' ? 'Pass Slip' : (string) ($locatorSlip->type ?? 'Locator Slip'),
                    'stages' => [
                        ['label' => 'Chief', 'status' => $displayStatus ?: 'pending'],
                    ],
                ];
            @endphp
            <x-approval-tracker :payload="$trackedLocatorPayload" event="locator-selected" empty="No locator slip to track yet." />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-8 md:p-10">
                        <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-50">
                            <x-profile-avatar :employee="$locatorSlip->employee" size="xl" variant="indigo" rounded="2xl" />
                            <div>
                                <h1 class="text-2xl font-black text-gray-900">
                                    {{ $locatorSlip->employee?->firstname }} {{ $locatorSlip->employee?->lastname }}
                                </h1>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-400">
                                    <span>{{ $locatorSlip->employee?->position ?: __('No position') }}</span>
                                    <span class="text-gray-300">|</span>
                                    <span>{{ $locatorSlip->employee?->division ?: __('No division') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="flex flex-col items-center justify-center py-6 bg-gray-50/50 rounded-2xl border border-gray-100">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 mb-2">{{ __('Locator Type') }}</span>
                                <h2 class="text-2xl font-black text-gray-800">{{ ($locatorSlip->type ?? '') === 'Personal' ? __('Pass Slip') : ($locatorSlip->type ?? __('N/A')) }}</h2>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">{{ __('Date Covered') }}</span>
                                    <div class="text-sm font-bold text-gray-700">
                                        {{ \Carbon\Carbon::parse($locatorSlip->date_covered)->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">{{ __('Time') }}</span>
                                    <div class="text-sm font-bold text-gray-700">
                                        {{ \Carbon\Carbon::parse($locatorSlip->time_from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($locatorSlip->time_to)->format('h:i A') }}
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">{{ __('Date Filed') }}</span>
                                    <div class="text-sm font-bold text-gray-700">
                                        {{ $locatorSlip->created_at?->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="relative pl-6 border-l-4 border-indigo-100 py-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">{{ __('Destination') }}</span>
                                    <p class="text-gray-700 font-medium leading-relaxed">{{ $locatorSlip->destination ?? __('N/A') }}</p>
                                </div>
                                <div class="relative pl-6 border-l-4 border-indigo-100 py-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2">{{ __('Purpose') }}</span>
                                    <p class="text-gray-700 font-medium leading-relaxed italic">"{{ $locatorSlip->purpose }}"</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="space-y-8">
                    @php
                        $statusClass = match($displayStatus) {
                            'approved' => 'text-green-500',
                            'rejected' => 'text-red-500',
                            default => 'text-orange-500',
                        };
                    @endphp
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-2 h-2 rounded-full {{ $displayStatus === 'approved' ? 'bg-green-400' : ($displayStatus === 'rejected' ? 'bg-red-400' : 'bg-orange-400 animate-pulse') }}"></div>
                            <span class="text-[10px] font-black uppercase {{ $statusClass }} tracking-widest">{{ ucfirst($displayStatus) }}</span>
                        </div>
                        <p class="text-[10px] text-gray-500 leading-relaxed font-medium">
                            @if($displayStatus === 'approved')
                                {{ __('This locator slip has been approved by the Division Chief.') }}
                            @elseif($displayStatus === 'rejected')
                                {{ __('This locator slip has been rejected.') }}
                            @else
                                {{ __('This locator slip is awaiting review from the Division Chief.') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
