<x-app-layout>
    <x-slot name="title">{{ __('Review Leave Application') }}</x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @php
                $backRoute = $leaveApplication->status === 'pending'
                    ? route('leave-applications.index')
                    : route('leave-applications.all');

                $trackedLeavePayload = [
                    'id' => (string) $leaveApplication->id,
                    'title' => (string) \Illuminate\Support\Str::of($leaveApplication->leaveType?->name ?? 'Leave')->replaceMatches('/\s+Leave\b/i', '')->trim(),
                    'type' => (string) \Illuminate\Support\Str::of($leaveApplication->leaveType?->name ?? 'Leave')->replaceMatches('/\s+Leave\b/i', '')->trim(),
                    'employee' => trim(($leaveApplication->employee?->firstname ?? '') . ' ' . ($leaveApplication->employee?->lastname ?? '')),
                    'stages' => [
                        ['label' => 'HR', 'status' => $leaveApplication->hrstaff_status ?: 'pending'],
                        ['label' => 'Chief', 'status' => $leaveApplication->chief_status ?: 'pending'],
                        ['label' => 'Regional Director', 'status' => $leaveApplication->rd_status ?: 'pending'],
                    ],
                ];

                $role = strtolower(auth()->user()->role ?? '');
                $isChief = $role === 'chief';
                $isHR = in_array($role, ['hrstaff', 'hr staff', 'admin'], true);
                $isDirector = in_array($role, ['regional director', 'regionaldirector', 'director'], true);

                $isMyTurn = false;
                $waitingMessage = '';

                if ($leaveApplication->status === 'pending') {
                    if ($isHR && $leaveApplication->hrstaff_status === 'pending') {
                        $isMyTurn = true;
                    } elseif ($isChief && $leaveApplication->chief_status === 'pending') {
                        if (in_array($leaveApplication->hrstaff_status, ['approved', 'rejected'], true)) {
                            $isMyTurn = true;
                        } else {
                            $waitingMessage = 'Waiting for HR Admin verification.';
                        }
                    } elseif ($isDirector && $leaveApplication->rd_status === 'pending') {
                        if ($leaveApplication->chief_status === 'approved') {
                            $isMyTurn = true;
                        } else {
                            $waitingMessage = 'Waiting for Division Chief approval.';
                        }
                    }
                }
            @endphp

            <div class="mb-5 flex items-center justify-between gap-3">
                <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back') }}
                </a>
                <a href="{{ route('leave-applications.print', $leaveApplication) }}" target="_blank" data-no-transition class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">
                    {{ __('Print Leave Form') }}
                </a>
            </div>

            <x-approval-tracker :payload="$trackedLeavePayload" event="leave-selected" empty="No leave approval process to track yet." />

            {{-- Review Action Above Form --}}
            <section class="mt-8 rounded-3xl border border-gray-100 bg-white p-5 shadow-sm sm:p-7">
                @if($isMyTurn)
                    <div class="mb-6 flex flex-col gap-3 border-b border-gray-100 pb-5 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-indigo-500">
                                {{ __('Current Review Stage') }}
                            </p>

                            <h3 class="mt-1 text-xl font-black text-gray-900">
                                {{ $isHR
                                    ? __('Verify Leave Application')
                                    : __('Review Leave Application') }}
                            </h3>

                            <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-500">
                                {{ __('Enter your remarks, then approve or reject the application. Review the complete form below before submitting your decision.') }}
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
                        action="{{ route('leave-applications.update', $leaveApplication->id) }}"
                        id="leaveStatusForm"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        {{-- Remarks --}}
                        <div>
                            <label
                                for="leaveRemarks"
                                class="mb-2 block text-xs font-black uppercase tracking-widest text-gray-600"
                            >
                                {{ __('Remarks / Notes') }}
                            </label>

                            <textarea
                                id="leaveRemarks"
                                name="remarks"
                                rows="4"
                                placeholder="{{ __('Enter remarks or notes here...') }}"
                                class="block w-full resize-y rounded-2xl border-gray-300 bg-gray-50 text-sm text-gray-800 shadow-sm transition focus:border-indigo-500 focus:bg-white focus:ring-indigo-500"
                            ></textarea>

                            <div class="mt-2 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-xs text-gray-400">
                                    {{ __('Remarks are required when rejecting the application.') }}
                                </p>

                                <span
                                    id="leaveRemarksError"
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
                                class="inline-flex min-w-44 items-center justify-center rounded-xl border border-red-300 bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2"
                            >
                                <i class="fa-solid fa-xmark mr-2"></i>
                                {{ __('Reject Request') }}
                            </button>

                            <button
                                type="submit"
                                name="status"
                                value="approved"
                                class="inline-flex min-w-44 items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-xs font-black uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                <i class="fa-solid fa-check mr-2"></i>

                                {{ $isHR
                                    ? __('Verify Request')
                                    : __('Approve Request') }}
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
                                        {{ __('This application has already been processed or is currently assigned to another approval stage.') }}
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
                                    {{ __('Application Processed') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </section>

            {{-- Full Leave Form Preview at Bottom --}}
            <section class="mt-6 overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
               

                <div class="bg-slate-100 p-3 sm:p-5">
    <div class="mx-auto w-full max-w-[1100px] overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <iframe
            id="leaveFormPreview"
            src="{{ route('leave-applications.print', [
                'leaveApplication' => $leaveApplication->id,
                'preview' => 1
            ]) }}"
            class="block w-full border-0 bg-white"
            style="height: 1300px;"
            scrolling="no"
            title="{{ __('Leave form print preview') }}"
        ></iframe>
    </div>
</div>
            </section>

            @if($leaveApplication->hrstaff_remarks || $leaveApplication->chief_remarks || $leaveApplication->rd_remarks)
                <section class="mt-6 overflow-hidden rounded-3xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">
                    <div class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-6 inline-block border-b-2 border-indigo-100 pb-1">Approver Remarks</div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        @if($leaveApplication->hrstaff_remarks)
                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400">HR Staff</span>
                                <p class="mt-2 whitespace-pre-line break-words text-sm font-semibold italic leading-relaxed text-gray-700">"{{ $leaveApplication->hrstaff_remarks }}"</p>
                            </div>
                        @endif
                        @if($leaveApplication->chief_remarks)
                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400">Chief</span>
                                <p class="mt-2 whitespace-pre-line break-words text-sm font-semibold italic leading-relaxed text-gray-700">"{{ $leaveApplication->chief_remarks }}"</p>
                            </div>
                        @endif
                        @if($leaveApplication->rd_remarks)
                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400">Regional Director</span>
                                <p class="mt-2 whitespace-pre-line break-words text-sm font-semibold italic leading-relaxed text-gray-700">"{{ $leaveApplication->rd_remarks }}"</p>
                            </div>
                        @endif
                    </div>
                </section>
            @endif
            </div>
        </div>
    </div>

    <script>
        (function() {
            const form = document.getElementById('leaveStatusForm');
            const remarksField = form?.querySelector('textarea[name="remarks"]');
            const errorSpan = document.getElementById('leaveRemarksError');

            remarksField?.addEventListener('input', function() {
                remarksField.classList.remove('ring-2', 'ring-red-500', 'border-red-500');
                if (errorSpan) {
                    errorSpan.classList.add('hidden');
                    errorSpan.textContent = '';
                }
            });

            form?.addEventListener('submit', function(e) {
                const action = document.activeElement ? document.activeElement.getAttribute('value') : null;
                const remarks = remarksField.value.trim();
                if (action === 'rejected' && !remarks) {
                    e.preventDefault();
                    remarksField.classList.add('ring-2', 'ring-red-500', 'border-red-500');
                    if (errorSpan) {
                        errorSpan.textContent = 'Remarks are required when rejecting this application.';
                        errorSpan.classList.remove('hidden');
                    }
                    remarksField.focus();
                }
            });
        })();
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const iframe = document.getElementById('leaveFormPreview');

        if (!iframe) {
            return;
        }

        function resizeLeaveFormPreview() {
            try {
                const iframeDocument =
                    iframe.contentDocument ||
                    iframe.contentWindow.document;

                if (!iframeDocument) {
                    return;
                }

                const bodyHeight =
                    iframeDocument.body?.scrollHeight || 0;

                const documentHeight =
                    iframeDocument.documentElement?.scrollHeight || 0;

                iframe.style.height =
                    Math.max(bodyHeight, documentHeight, 1300) + 'px';
            } catch (error) {
                console.warn(
                    'Unable to resize leave form preview.',
                    error
                );
            }
        }

        iframe.addEventListener('load', function () {
            resizeLeaveFormPreview();

            setTimeout(resizeLeaveFaormPreview, 300);
            setTimeout(resizeLeaveFormPreview, 800);
        });

        window.addEventListener(
            'resize',
            resizeLeaveFormPreview
        );
    });
</script>
</x-app-layout>
