@forelse($leaves as $leaf)
    @php
        $hrStatus = strtolower($leaf->hrstaff_status ?: 'pending');
        $chiefStatus = strtolower($leaf->chief_status ?: 'pending');
        $rdStatus = strtolower($leaf->rd_status ?: 'pending');

        $stages = [
            [
                'label' => 'HR',
                'status' => $hrStatus,
            ],
            [
                'label' => 'Chief',
                'status' => $chiefStatus,
            ],
            [
                'label' => 'Regional Director',
                'status' => $rdStatus,
            ],
        ];

        $approvedCount = collect($stages)
            ->where('status', 'approved')
            ->count();

        $hasRejected = collect($stages)
            ->contains(
                fn ($stage) => $stage['status'] === 'rejected'
            );

        $displayStatus = $hasRejected
            ? 'rejected'
            : ($approvedCount === 3 ? 'approved' : 'pending');

        $statusClass = match ($displayStatus) {
            'approved' => 'bg-green-500 text-white',
            'rejected' => 'bg-red-500 text-white',
            default => 'border border-orange-100 bg-orange-50 text-orange-700',
        };

        $approvalDotClass = fn (string $status) => match ($status) {
            'approved' => 'bg-green-500',
            'rejected' => 'bg-red-500',
            default => 'bg-gray-300',
        };

        $leaveTypeName = Str::of($leaf->leaveType?->name ?? '')
            ->replaceMatches('/\s+Leave\b/i', '')
            ->trim();

        $employeeName = trim(
            ($leaf->employee?->firstname ?? '') . ' ' .
            ($leaf->employee?->lastname ?? '')
        );

        $hrStaffName = $leaf->hrstaff
            ? trim(
                ($leaf->hrstaff->firstname ?? '') . ' ' .
                ($leaf->hrstaff->lastname ?? '')
            )
            : __('HR Admin');

        $chiefName = $leaf->chief
            ? trim(
                ($leaf->chief->firstname ?? '') . ' ' .
                ($leaf->chief->lastname ?? '')
            )
            : __('Division Chief');

        $regionalDirectorName = $leaf->regionalDirector
            ? trim(
                ($leaf->regionalDirector->firstname ?? '') . ' ' .
                ($leaf->regionalDirector->lastname ?? '')
            )
            : __('Regional Director');

        $previewPayload = [
            'title' => (string) $leaveTypeName,
            'date' => \Carbon\Carbon::parse($leaf->date_filed)->format('M d, Y'),
            'status' => (string) $displayStatus,
            'remarks' => trim(
                collect([
                    $leaf->rd_remarks,
                    $leaf->hrstaff_remarks,
                    $leaf->chief_remarks,
                    $leaf->remarks,
                ])->filter()->implode(' ')
            ) ?: 'No remarks available.',
            'printUrl' => route(
                'leave-applications.print',
                [
                    'leaveApplication' => $leaf->id,
                    'preview' => 1,
                ]
            ),
            'directPrintUrl' => route(
                'leave-applications.print',
                $leaf->id
            ),
        ];

        $role = strtolower(auth()->user()->role ?? '');

        $isHR = in_array(
            $role,
            ['hrstaff', 'hr staff', 'admin'],
            true
        );

        $isReviewMode = ($actionMode ?? 'view') === 'review';
    @endphp

    <tr class="transition-colors hover:bg-gray-50/70">

        {{-- Date Filed --}}
        <td class="px-3 py-3 align-middle">
            <div class="min-w-0 break-words text-sm font-medium leading-5 text-gray-700">
                {{ \Carbon\Carbon::parse($leaf->date_filed)->format('M d, Y') }}
            </div>
        </td>

        {{-- Employee Name --}}
        <td class="px-3 py-3 align-middle">
            <div
                class="min-w-0 break-words text-sm font-bold leading-5 text-gray-900 [overflow-wrap:anywhere]"
                title="{{ $employeeName }}"
            >
                {{ $employeeName ?: __('N/A') }}
            </div>
        </td>

        {{-- Leave Type --}}
        <td class="px-3 py-3 align-middle">
            <div
                class="min-w-0 break-words text-sm font-bold leading-5 text-gray-900 [overflow-wrap:anywhere]"
                title="{{ $leaveTypeName }}"
            >
                {{ $leaveTypeName ?: __('N/A') }}
            </div>
        </td>

        

        {{-- HR: Leave Credit Certification --}}
        <td class="px-3 py-3 align-middle">
            <div class="flex min-w-0 items-start gap-2">
                <span
                    class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $approvalDotClass($hrStatus) }}"
                    title="{{ ucfirst($hrStatus) }}"
                ></span>

                <span
                    class="min-w-0 flex-1 break-words text-xs font-semibold leading-5 text-gray-700 [overflow-wrap:anywhere]"
                    title="{{ $hrStaffName }}"
                >
                    {{ $hrStaffName }}
                </span>
            </div>
        </td>

        {{-- Chief: Recommending Approval --}}
        <td class="px-3 py-3 align-middle">
            <div class="flex min-w-0 items-start gap-2">
                <span
                    class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $approvalDotClass($chiefStatus) }}"
                    title="{{ ucfirst($chiefStatus) }}"
                ></span>

                <span
                    class="min-w-0 flex-1 break-words text-xs font-semibold leading-5 text-gray-700 [overflow-wrap:anywhere]"
                    title="{{ $chiefName }}"
                >
                    {{ $chiefName }}
                </span>
            </div>
        </td>

        {{-- Regional Director: Final Approval --}}
        <td class="px-3 py-3 align-middle">
            <div class="flex min-w-0 items-start gap-2">
                <span
                    class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $approvalDotClass($rdStatus) }}"
                    title="{{ ucfirst($rdStatus) }}"
                ></span>

                <span
                    class="min-w-0 flex-1 break-words text-xs font-semibold leading-5 text-gray-700 [overflow-wrap:anywhere]"
                    title="{{ $regionalDirectorName }}"
                >
                    {{ $regionalDirectorName }}
                </span>
            </div>
        </td>
{{-- Status --}}
       <td class="px-3 py-3 text-center align-middle">
            <span
                class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold uppercase {{ $statusClass }}"
            >
                {{ ucfirst($displayStatus) }}
            </span>
        </td>
        {{-- Actions --}}
       <td class="px-3 py-3 text-center align-middle">
    <div class="mx-auto grid w-fit grid-cols-2 place-items-center gap-x-3 gap-y-2">
        {{-- View --}}
        <a
            href="{{ route('leave-applications.show', $leaf) }}"
            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-indigo-700 transition-colors hover:bg-indigo-50 hover:text-indigo-900"
            title="{{ __('View details') }}"
            aria-label="{{ __('View details') }}"
        >
            <i class="fa-solid fa-eye text-sm"></i>
        </a>

        {{-- Print --}}
        <a
            href="{{ route('leave-applications.print', $leaf) }}"
            target="_blank"
            rel="noopener"
            data-no-transition
            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900"
            title="{{ __('Print leave form') }}"
            aria-label="{{ __('Print leave form') }}"
        >
            <i class="fa-solid fa-print text-sm"></i>
        </a>

        @if(($actionMode ?? 'view') === 'review')
            {{-- Approve --}}
            <form
                action="{{ route('leave-applications.update', $leaf->id) }}"
                method="POST"
                class="m-0 flex h-8 w-8 items-center justify-center"
                onsubmit="return confirm('{{ $isHR
                    ? __('Verify this leave application?')
                    : __('Approve this leave application?') }}');"
            >
                @csrf
                @method('PUT')

                <input type="hidden" name="status" value="approved">

                <button
                    type="submit"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-green-600 transition-colors hover:bg-green-50 hover:text-green-800"
                    title="{{ $isHR ? __('Verify') : __('Approve') }}"
                    aria-label="{{ $isHR ? __('Verify') : __('Approve') }}"
                >
                    <i class="fa-solid fa-check text-sm"></i>
                </button>
            </form>

            {{-- Reject --}}
            <form
                action="{{ route('leave-applications.update', $leaf->id) }}"
                method="POST"
                id="leaveRejectRowForm_{{ $leaf->id }}"
                class="m-0 flex h-8 w-8 items-center justify-center"
            >
                @csrf
                @method('PUT')

                <input type="hidden" name="status" value="rejected">

                <input
                    type="hidden"
                    name="remarks"
                    id="leaveRejectRowRemarks_{{ $leaf->id }}"
                    value=""
                >

                <button
                    type="button"
                    onclick="submitLeaveRejectRow(
                        {{ $leaf->id }},
                        {{ $isHR ? 'true' : 'false' }}
                    )"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-600 transition-colors hover:bg-red-50 hover:text-red-800"
                    title="{{ __('Decline') }}"
                    aria-label="{{ __('Decline') }}"
                >
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </form>
        @endif
    </div>
</td>

@empty
    <tr>
        <td colspan="8" class="px-4 py-12 text-center">
            <div class="mb-2 text-gray-400">
                <svg
                    class="mx-auto h-12 w-12 text-gray-300"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
            </div>

            <p class="font-medium italic text-gray-500">
                {{ $emptyMessage ?? __('No leave applications found.') }}
            </p>
        </td>
    </tr>
@endforelse

@if(($actionMode ?? 'view') === 'review')
    @once
        <script>
            window.submitLeaveRejectRow = function (id, isHR) {
                let remarks = '';

                if (isHR) {
                    remarks = prompt(
                        'Remarks are required when rejecting this application. Please enter remarks:'
                    );

                    if (remarks === null) {
                        return;
                    }

                    remarks = remarks.trim();

                    if (!remarks) {
                        alert('Remarks are required to reject.');
                        return;
                    }
                } else {
                    const confirmed = confirm(
                        'Are you sure you want to decline this application?'
                    );

                    if (!confirmed) {
                        return;
                    }
                }

                const form = document.getElementById(
                    'leaveRejectRowForm_' + id
                );

                const remarksInput = document.getElementById(
                    'leaveRejectRowRemarks_' + id
                );

                if (!form) {
                    return;
                }

                if (remarksInput) {
                    remarksInput.value = remarks;
                }

                form.submit();
            };
        </script>
    @endonce
@endif