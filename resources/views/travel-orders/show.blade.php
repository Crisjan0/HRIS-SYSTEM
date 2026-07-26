<x-app-layout>
    <x-slot name="title">{{ __('Travel Authority Details') }}</x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @php
                $role = strtolower(auth()->user()->role ?? '');

                $isApprover = in_array(
                    $role,
                    ['admin', 'hrstaff', 'recordofficer', 'regionaldirector'],
                    true
                );

                $isRecordsOfficer = $role === 'recordofficer';
                $isDirector = $role === 'regionaldirector';

                $recordStatus = $travelOrder->recordofficer_status ?: 'pending';
                $rdStatus = $travelOrder->rd_status ?: 'pending';
                $mainStatus = $travelOrder->status ?: 'pending';

                $trackedEmployee = trim(
                    ($travelOrder->employee?->firstname ?? '') . ' ' .
                    ($travelOrder->employee?->lastname ?? '')
                );

                $trackedOrderPayload = [
                    'id' => (string) $travelOrder->id,
                    'title' => $isApprover
                        ? $trackedEmployee
                        : (string) $travelOrder->places_of_travel,
                    'employee' => $trackedEmployee,
                    'type' => (string) $travelOrder->places_of_travel,
                    'stages' => [
                        [
                            'label' => 'Record Officer',
                            'status' => $recordStatus,
                        ],
                        [
                            'label' => 'Regional Director',
                            'status' => $rdStatus,
                        ],
                    ],
                ];

                $isMyTurn = false;
                $waitingMessage = '';

                if ($mainStatus === 'pending') {
                    if ($isRecordsOfficer && $recordStatus === 'pending') {
                        $isMyTurn = true;
                    } elseif (
                        $isDirector &&
                        $recordStatus === 'approved' &&
                        $rdStatus === 'pending'
                    ) {
                        $isMyTurn = true;
                    } elseif (
                        $isDirector &&
                        $recordStatus !== 'approved'
                    ) {
                        $waitingMessage = 'Waiting for Record Officer review.';
                    } elseif (
                        ! $isRecordsOfficer &&
                        ! $isDirector
                    ) {
                        $waitingMessage = 'Waiting for Travel Authority approval.';
                    }
                }
            @endphp

            {{-- Top actions --}}
            <div class="mb-5 flex items-center justify-between gap-3">
                <a
                    href="{{ $isApprover
                        ? route('hr.travel-orders.index')
                        : route('travel-orders.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-blue-900 shadow-sm transition hover:border-blue-200 hover:text-blue-800"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                    {{ __('Back') }}
                </a>

                <a
                    href="{{ route('travel-orders.print', $travelOrder) }}"
                    target="_blank"
                    rel="noopener"
                    data-no-transition
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-900 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-blue-800"
                >
                    <i class="fa-solid fa-print"></i>

                    {{ __('Print Travel Authority') }}
                </a>
            </div>

            {{-- Approval tracker --}}
            <x-approval-tracker
                :payload="$trackedOrderPayload"
                event="travel-selected"
                empty="No travel authority to track yet."
            />

            @if($travelOrder->recordofficer_remarks || $travelOrder->chief_remarks || $travelOrder->hrstaff_remarks || $travelOrder->rd_remarks)
                <div class="mb-6 bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100 p-6 md:p-8">
                    <div class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-6 inline-block border-b-2 border-indigo-100 pb-1">Approver Remarks</div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        @if($travelOrder->recordofficer_remarks)
                            <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100">
                                <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Records Officer</span>
                                <p class="text-sm font-semibold text-gray-700 leading-relaxed italic">"{{ $travelOrder->recordofficer_remarks }}"</p>
                            </div>
                        @endif
                        @if($travelOrder->chief_remarks)
                            <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100">
                                <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Chief</span>
                                <p class="text-sm font-semibold text-gray-700 leading-relaxed italic">"{{ $travelOrder->chief_remarks }}"</p>
                            </div>
                        @endif
                        @if($travelOrder->hrstaff_remarks)
                            <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100">
                                <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">HR Staff</span>
                                <p class="text-sm font-semibold text-gray-700 leading-relaxed italic">"{{ $travelOrder->hrstaff_remarks }}"</p>
                            </div>
                        @endif
                        @if($travelOrder->rd_remarks)
                            <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100">
                                <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Regional Director</span>
                                <p class="text-sm font-semibold text-gray-700 leading-relaxed italic">"{{ $travelOrder->rd_remarks }}"</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Review Action Above the Form --}}
            <section class="mt-8 rounded-3xl border border-gray-100 bg-white p-5 shadow-sm sm:p-7">
                @if($isMyTurn)
                    <div class="mb-6 flex flex-col gap-3 border-b border-gray-100 pb-5 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-indigo-500">
                                {{ __('Current Review Stage') }}
                            </p>

                            <h3 class="mt-1 text-xl font-black text-gray-900">
                                {{ $isRecordsOfficer
                                    ? __('Review Travel Authority')
                                    : __('Approve Travel Authority') }}
                            </h3>

                            <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-500">
                                {{ __('Enter your remarks, then recommend, approve, or reject the travel authority. Review the complete form below before submitting your decision.') }}
                            </p>
                        </div>

                        <div class="inline-flex shrink-0 items-center gap-2 rounded-full bg-orange-50 px-3 py-2">
                            <span class="h-2.5 w-2.5 animate-pulse rounded-full bg-orange-400"></span>

                            <span class="text-[10px] font-black uppercase tracking-widest text-orange-600">
                                {{ __('Your Review Needed') }}
                            </span>
                        </div>
                    </div>

                    <form
                        id="travelOrderStatusForm"
                        action="{{ route('travel-orders.update-status', $travelOrder->id) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        {{-- Remarks --}}
                        <div>
                            <label
                                for="travel_order_remarks"
                                class="mb-2 block text-xs font-black uppercase tracking-widest text-gray-600"
                            >
                                {{ __('Remarks / Notes') }}
                            </label>

                            <textarea
                                id="travel_order_remarks"
                                name="remarks"
                                rows="4"
                                placeholder="{{ __('Enter remarks or notes here...') }}"
                                class="block w-full resize-y rounded-2xl border-gray-300 bg-gray-50 text-sm text-gray-800 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"
                            ></textarea>

                            <div class="mt-2 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-xs text-gray-400">
                                    {{ __('Remarks are required when rejecting the travel authority.') }}
                                </p>

                                <span
                                    id="travelOrderRemarksError"
                                    class="hidden text-xs font-semibold text-red-600"
                                ></span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button
                                type="submit"
                                name="status"
                                value="rejected"
                                class="inline-flex min-w-48 items-center justify-center rounded-xl border border-red-300 bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2"
                            >
                                <i class="fa-solid fa-xmark mr-2"></i>
                                {{ __('Reject Travel Authority') }}
                            </button>

                            <button
                                type="submit"
                                name="status"
                                value="approved"
                                class="inline-flex min-w-48 items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                <i class="fa-solid fa-check mr-2"></i>

                                {{ $isRecordsOfficer
                                    ? __('Recommend Travel Authority')
                                    : __('Approve Travel Authority') }}
                            </button>
                        </div>
                    </form>
                @else
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-start gap-4">
                            <div class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                <i class="fa-solid fa-lock"></i>
                            </div>

                            <div>
                                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700">
                                    {{ __('Review Action Unavailable') }}
                                </h3>

                                <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-500">
                                    @if($waitingMessage)
                                        {{ $waitingMessage }}
                                    @else
                                        {{ __('This travel authority has already been processed or is currently assigned to another approval stage.') }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="inline-flex shrink-0 items-center gap-2 rounded-full px-3 py-2
                            {{ $waitingMessage ? 'bg-yellow-50' : 'bg-green-50' }}">
                            @if($waitingMessage)
                                <span class="h-2.5 w-2.5 animate-pulse rounded-full bg-yellow-400"></span>

                                <span class="text-[10px] font-black uppercase tracking-widest text-yellow-600">
                                    {{ __('In Progress') }}
                                </span>
                            @else
                                <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>

                                <span class="text-[10px] font-black uppercase tracking-widest text-green-600">
                                    {{ __('Travel Authority Processed') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </section>

            {{-- Full Travel Authority Form --}}
            <section class="mt-6 overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
                <div class="bg-slate-100 p-3 sm:p-5">
                    <div class="mx-auto w-full max-w-[1100px] overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <iframe
                            id="travelAuthorityPreview"
                            src="{{ route('travel-orders.preview', $travelOrder) }}"
                            class="block w-full border-0 bg-white"
                            style="height: 1300px;"
                            scrolling="no"
                            title="{{ __('Travel authority print preview') }}"
                        ></iframe>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const iframe = document.getElementById('travelAuthorityPreview');
            const form = document.getElementById('travelOrderStatusForm');
            const remarksField = document.getElementById('travel_order_remarks');
            const errorSpan = document.getElementById('travelOrderRemarksError');

            function resizeTravelAuthorityPreview() {
                if (!iframe) {
                    return;
                }

                try {
                    const iframeDocument =
                        iframe.contentDocument ||
                        iframe.contentWindow?.document;

                    if (!iframeDocument) {
                        return;
                    }

                    const bodyHeight = iframeDocument.body?.scrollHeight || 0;
                    const documentHeight =
                        iframeDocument.documentElement?.scrollHeight || 0;

                    iframe.style.height =
                        Math.max(bodyHeight, documentHeight, 1300) + 'px';
                } catch (error) {
                    console.warn(
                        'Unable to resize the travel authority preview.',
                        error
                    );
                }
            }

            iframe?.addEventListener('load', function () {
                resizeTravelAuthorityPreview();

                setTimeout(resizeTravelAuthorityPreview, 300);
                setTimeout(resizeTravelAuthorityPreview, 800);
            });

            window.addEventListener(
                'resize',
                resizeTravelAuthorityPreview
            );

            remarksField?.addEventListener('input', function () {
                remarksField.classList.remove(
                    'ring-2',
                    'ring-red-500',
                    'border-red-500'
                );

                if (errorSpan) {
                    errorSpan.classList.add('hidden');
                    errorSpan.textContent = '';
                }
            });

            form?.addEventListener('submit', function (event) {
                const submitter = event.submitter;
                const action = submitter?.value;
                const remarks = remarksField?.value.trim() || '';

                if (action === 'rejected' && !remarks) {
                    event.preventDefault();

                    remarksField?.classList.add(
                        'ring-2',
                        'ring-red-500',
                        'border-red-500'
                    );

                    if (errorSpan) {
                        errorSpan.textContent =
                            'Remarks are required when rejecting this travel authority.';
                        errorSpan.classList.remove('hidden');
                    }

                    remarksField?.focus();
                }
            });
        });
    </script>
</x-app-layout>