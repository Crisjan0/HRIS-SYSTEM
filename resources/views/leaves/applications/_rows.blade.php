@forelse($leaves as $leaf)
    @php
        $hrStatus = strtolower($leaf->hrstaff_status ?: 'pending');
        $chiefStatus = strtolower($leaf->chief_status ?: 'pending');
        $rdStatus = strtolower($leaf->rd_status ?: 'pending');

        $stages = [
            ['label' => 'HR', 'status' => $hrStatus],
            ['label' => 'Chief', 'status' => $chiefStatus],
            ['label' => 'Regional Director', 'status' => $rdStatus],
        ];

        $approvedCount = collect($stages)
            ->where('status', 'approved')
            ->count();

        $hasRejected = collect($stages)
            ->contains(fn ($stage) => $stage['status'] === 'rejected');

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

        $employeePosition = $leaf->employee?->position ?: __('No position');

        $actionTitle = ($actionMode ?? 'view') === 'review'
            ? __('Review application')
            : __('View details');

        $role = strtolower(auth()->user()->role ?? '');
        $isHR = in_array($role, ['hrstaff', 'hr staff', 'admin'], true);
    @endphp

    <tr class="transition-colors hover:bg-gray-50/70">
        {{-- Name --}}
        <td class="px-4 py-3 align-middle">
            <div
                class="truncate text-sm font-bold text-gray-900"
                title="{{ $employeeName }}"
            >
                {{ $employeeName ?: __('N/A') }}
            </div>

            <div
                class="truncate whitespace-nowrap text-xs text-gray-400"
                title="{{ $employeePosition }}"
            >
                {{ $employeePosition }}
            </div>
        </td>

        {{-- Leave Type --}}
        <td class="px-4 py-3 align-middle">
            <div
                class="truncate text-sm font-bold text-gray-900"
                title="{{ $leaveTypeName }}"
            >
                {{ $leaveTypeName ?: __('N/A') }}
            </div>
        </td>

        {{-- HR: Leave Credit Certification --}}
        <td class="px-4 py-3 align-middle">
            <div class="flex items-center gap-2">
                <span
                    class="h-2.5 w-2.5 shrink-0 rounded-full {{ $approvalDotClass($hrStatus) }}"
                    title="{{ ucfirst($hrStatus) }}"
                ></span>

                <span class="truncate text-xs font-medium text-gray-500">
                    {{ __('HR') }}
                </span>
            </div>
        </td>

        {{-- Chief: Recommendation --}}
        <td class="px-4 py-3 align-middle">
            <div class="flex items-center gap-2">
                <span
                    class="h-2.5 w-2.5 shrink-0 rounded-full {{ $approvalDotClass($chiefStatus) }}"
                    title="{{ ucfirst($chiefStatus) }}"
                ></span>

                <span class="truncate text-xs font-medium text-gray-500">
                    {{ __('Chief') }}
                </span>
            </div>
        </td>

        {{-- Regional Director: Final Approval --}}
        <td class="px-4 py-3 align-middle">
            <div class="flex items-center gap-2">
                <span
                    class="h-2.5 w-2.5 shrink-0 rounded-full {{ $approvalDotClass($rdStatus) }}"
                    title="{{ ucfirst($rdStatus) }}"
                ></span>

                <span class="truncate text-xs font-medium text-gray-500">
                    {{ __('Regional Director') }}
                </span>
            </div>
        </td>

        {{-- Overall Status --}}
        <td class="px-4 py-3 align-middle whitespace-nowrap">
            <span
                class="inline-flex rounded-full px-3 py-1 text-xs font-semibold leading-5 shadow-sm {{ $statusClass }}"
            >
                {{ ucfirst($displayStatus) }}
            </span>
        </td>

        {{-- Date Filed --}}
        <td class="px-4 py-3 align-middle">
            <div class="whitespace-nowrap text-sm font-medium text-gray-700">
                {{ \Carbon\Carbon::parse($leaf->date_filed)->format('M d, Y') }}
            </div>
        </td>

        {{-- Actions --}}
        <td class="px-4 py-3 text-right align-middle">
            <div class="flex items-center justify-end gap-1.5">
                <a
                    href="{{ route('leave-applications.show', $leaf->id) }}"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-indigo-700 transition-colors hover:bg-indigo-50 hover:text-indigo-900"
                    title="{{ $actionTitle }}"
                    aria-label="{{ $actionTitle }}"
                >
                    <i class="fa-solid fa-eye"></i>
                </a>

                @if(($actionMode ?? 'view') === 'review')
                    <form
                        action="{{ route('leave-applications.update', $leaf->id) }}"
                        method="POST"
                        onsubmit="return confirm('{{ $isHR ? __('Verify this leave application?') : __('Approve this leave application?') }}');"
                    >
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="status" value="approved">

                        <button
                            type="submit"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-green-600 transition-colors hover:bg-green-50 hover:text-green-800"
                            title="{{ $isHR ? __('Verify') : __('Approve') }}"
                            aria-label="{{ $isHR ? __('Verify') : __('Approve') }}"
                        >
                            <i class="fa-solid fa-check"></i>
                        </button>
                    </form>

                    <form
                        action="{{ route('leave-applications.update', $leaf->id) }}"
                        method="POST"
                        id="leaveRejectRowForm_{{ $leaf->id }}"
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
                            onclick="submitLeaveRejectRow({{ $leaf->id }}, {{ $isHR ? 'true' : 'false' }})"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 transition-colors hover:bg-red-50 hover:text-red-800"
                            title="{{ __('Decline') }}"
                            aria-label="{{ __('Decline') }}"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </form>
                @endif
            </div>
        </td>
    </tr>

@empty
    <tr>
        <td colspan="8" class="px-4 py-12 text-center">
            <div class="mb-2 text-gray-400">
                <svg
                    class="mx-auto h-12 w-12 text-gray-300"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
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

                    if (remarks === null) return;

                    remarks = remarks.trim();

                    if (!remarks) {
                        alert('Remarks are required to reject.');
                        return;
                    }
                } else {
                    if (!confirm('Are you sure you want to decline this application?')) {
                        return;
                    }
                }

                const form = document.getElementById('leaveRejectRowForm_' + id);
                const remarksInput = document.getElementById(
                    'leaveRejectRowRemarks_' + id
                );

                if (!form) return;

                if (remarksInput) {
                    remarksInput.value = remarks;
                }

                form.submit();
            };
        </script>
    @endonce
@endif