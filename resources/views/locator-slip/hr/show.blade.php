<x-app-layout>
    <x-slot name="title">{{ __('Review Locator Slip') }}</x-slot>

    @php
        $employee = $locatorSlip->employee;
        $role = strtolower(auth()->user()->role ?? '');
        $canApprove = $role === 'chief' && $locatorSlip->status === 'pending';
        $canReject = $role === 'chief' && $locatorSlip->status === 'pending';
        $statusClass = match($locatorSlip->status) {
            'approved' => 'border-green-100 bg-green-50 text-green-700',
            'rejected' => 'border-red-100 bg-red-50 text-red-700',
            default => 'border-orange-100 bg-orange-50 text-orange-700',
        };
        $statusLabel = $locatorSlip->status === 'approved by chief' ? 'Approved' : ucfirst($locatorSlip->status);
    @endphp

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between gap-3">
                <a href="{{ route('hr.locator-slips.index', ['tab' => $locatorSlip->status === 'pending' ? 'pending' : 'all']) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back') }}
                </a>
                <a href="{{ route('locator-slips.print', $locatorSlip) }}" target="_blank" data-no-transition class="inline-flex items-center gap-2 rounded-lg bg-indigo-700 px-4 py-2 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-800">
                    <i class="fa-solid fa-print"></i>
                    {{ __('Print') }}
                </a>
            </div>

            @php
                $displayStatus = $locatorSlip->status === 'approved by chief' ? 'approved' : $locatorSlip->status;
                $trackedLocatorPayload = [
                    'id' => (string) $locatorSlip->id,
                    'title' => trim(($employee?->firstname ?? '') . ' ' . ($employee?->lastname ?? '')),
                    'employee' => trim(($employee?->firstname ?? '') . ' ' . ($employee?->lastname ?? '')),
                    'type' => ($locatorSlip->type ?? '') === 'Personal' ? 'Pass Slip' : (string) ($locatorSlip->type ?: 'Locator Slip'),
                    'stages' => [
                        ['label' => 'Chief', 'status' => $displayStatus ?: 'pending'],
                    ],
                ];
            @endphp
            <x-approval-tracker :payload="$trackedLocatorPayload" event="locator-selected" empty="No locator slip to track yet." />

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-8">
                    <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white p-8 shadow-sm md:p-10">
                        <div class="mb-8 flex items-center gap-4 border-b border-gray-50 pb-8">
                            <x-profile-avatar :employee="$employee" size="xl" variant="indigo" rounded="2xl" />
                            <div class="min-w-0">
                                <h1 class="truncate text-2xl font-black text-gray-900">
                                    {{ $employee->firstname }} {{ $employee->lastname }}
                                </h1>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    {{ $employee->position ?: __('No position') }}
                                    <span class="mx-1 text-gray-300">|</span>
                                    {{ $employee->division ?: __('No division') }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="flex flex-col items-center justify-center rounded-2xl border border-gray-100 bg-gray-50/50 py-6">
                                <span class="mb-2 text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400">{{ __('Locator Slip Type') }}</span>
                                <h2 class="text-2xl font-black text-gray-800">{{ ($locatorSlip->type ?? '') === 'Personal' ? __('Pass Slip') : ($locatorSlip->type ?: __('N/A')) }}</h2>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
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
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 italic">{{ __('Status') }}</span>
                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>

                            <div class="relative border-l-4 border-indigo-100 py-2 pl-6">
                                <span class="mb-2 block text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Destination') }}</span>
                                <p class="font-medium uppercase leading-relaxed text-gray-700">{{ $locatorSlip->destination ?: __('N/A') }}</p>
                            </div>

                            <div class="relative border-l-4 border-indigo-100 py-2 pl-6">
                                <span class="mb-2 block text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Purpose') }}</span>
                                <p class="font-medium leading-relaxed text-gray-700 italic">"{{ $locatorSlip->purpose }}"</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="space-y-8">
                    @if($canApprove || $canReject)
                        <div class="sticky top-12 rounded-3xl bg-indigo-600 p-8 text-white shadow-2xl shadow-indigo-200">
                            <div class="mb-8">
                                <h3 class="mb-2 text-xl font-black uppercase tracking-widest">{{ __('Review Action') }}</h3>
                                <p class="text-xs font-medium text-indigo-200">{{ __('Verify the locator slip details before approving or declining the request.') }}</p>
                            </div>

                            <div class="space-y-3 pt-2">
                                @if($canApprove)
                                    <form action="{{ route('locator-slips.approve', $locatorSlip) }}" method="POST" onsubmit="return confirm('{{ __('Approve this locator slip?') }}');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full rounded-2xl bg-white px-6 py-4 text-xs font-black uppercase tracking-widest text-indigo-600 shadow-lg transition-all hover:-translate-y-1 hover:bg-indigo-50">
                                            {{ __('Approve Request') }}
                                        </button>
                                    </form>
                                @endif
                                @if($canReject)
                                    <form action="{{ route('locator-slips.reject', $locatorSlip) }}" method="POST" onsubmit="return confirm('{{ __('Decline this locator slip?') }}');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full rounded-2xl border-2 border-indigo-400 bg-indigo-500/50 px-6 py-4 text-xs font-black uppercase tracking-widest text-white transition-all hover:bg-indigo-500">
                                            {{ __('Decline Request') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6">
                        <div class="mb-4 flex items-center gap-2">
                            @if($canApprove || $canReject)
                                <div class="h-2 w-2 rounded-full bg-orange-400 animate-ping"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Your Review Needed') }}</span>
                            @elseif($locatorSlip->status === 'pending')
                                <div class="h-2 w-2 rounded-full bg-orange-400 animate-pulse"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-yellow-600">{{ __('In Progress') }}</span>
                            @else
                                <div class="h-2 w-2 rounded-full bg-green-400"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-green-500">{{ __('Application Processed') }}</span>
                            @endif
                        </div>
                        <p class="text-[10px] font-medium leading-relaxed text-gray-500">
                            @if($canApprove || $canReject)
                                {{ __('Your decision will finalize this locator slip request. The employee will see the updated status after submission.') }}
                            @elseif($locatorSlip->status === 'pending')
                                {{ __('This locator slip is waiting for the Division Chief review.') }}
                            @else
                                {{ __('This locator slip has already been finalized.') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
