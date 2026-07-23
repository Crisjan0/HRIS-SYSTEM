<x-app-layout>
    <x-slot name="title">{{ __('Leave Request Details') }}</x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            <div class="mb-5 flex items-center justify-between gap-3">
                <a href="{{ route('leaves.index') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-bold text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-indigo-600">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    {{ __('Back') }}
                </a>
                <a href="{{ route('leaves.print', $leaf) }}" target="_blank" data-no-transition class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">
                    {{ __('Print Leave Form') }}
                </a>
            </div>

            @php
                $trackedLeavePayload = [
                    'id' => (string) $leaf->id,
                    'title' => (string) Str::of($leaf->leaveType?->name ?? 'Leave')->replaceMatches('/\s+Leave\b/i', '')->trim(),
                    'type' => (string) Str::of($leaf->leaveType?->name ?? 'Leave')->replaceMatches('/\s+Leave\b/i', '')->trim(),
                    'stages' => [
                        ['label' => 'HR', 'status' => $leaf->hrstaff_status ?: 'pending'],
                        ['label' => 'Chief', 'status' => $leaf->chief_status ?: 'pending'],
                        ['label' => 'Regional Director', 'status' => $leaf->rd_status ?: 'pending'],
                    ],
                ];
            @endphp
            <x-approval-tracker :payload="$trackedLeavePayload" event="leave-selected" empty="No leave approval process to track yet." />
            @php
                $leaveRequest = $leaf;
            @endphp

            @php
                $leave = $leaveRequest;
                $employee = $leave->employee;
                $leaveType = trim((string) ($leave->leaveType?->name ?? 'Leave'));
                $leaveTypeLower = strtolower($leaveType);
                $reason = trim((string) $leave->reason);
                $reasonLower = strtolower($reason);
                $start = \Carbon\Carbon::parse($leave->start_date);
                $end = \Carbon\Carbon::parse($leave->end_date);
                $inclusiveDates = $start->isSameDay($end)
                    ? $start->format('F d, Y')
                    : $start->format('M d, Y') . ' - ' . $end->format('M d, Y');
                $duration = $leave->duration;
                $typeCheck = fn (string $needle) => str_contains($leaveTypeLower, $needle);
                $isVacationLike = $typeCheck('vacation') || $typeCheck('special privilege');
                $isSick = $typeCheck('sick');
                $isAbroad = str_contains($reasonLower, 'abroad');
                $isHospital = str_contains($reasonLower, 'hospital');
                $isMaster = str_contains($reasonLower, 'master');
                $isBar = str_contains($reasonLower, 'bar') || str_contains($reasonLower, 'board');
                $isMonetization = str_contains($reasonLower, 'monetization') || str_contains($leaveTypeLower, 'monetization');
                $isTerminal = str_contains($reasonLower, 'terminal') || str_contains($leaveTypeLower, 'terminal');

                $detailContext = __('General leave details');
                if ($isVacationLike) {
                    $detailContext = $isAbroad ? __('Abroad') : __('Within the Philippines');
                } elseif ($isSick) {
                    $detailContext = $isHospital ? __('In Hospital') : __('Out Patient');
                } elseif ($typeCheck('women')) {
                    $detailContext = __('Special Leave Benefits for Women');
                } elseif ($typeCheck('study')) {
                    $detailContext = $isMaster ? __("Completion of Master's Degree") : ($isBar ? __('BAR/Board Examination Review') : __('Study Leave'));
                } elseif ($isMonetization) {
                    $detailContext = __('Monetization of Leave Credits');
                } elseif ($isTerminal) {
                    $detailContext = __('Terminal Leave');
                }

                $statusColors = [
                    'pending' => 'text-orange-600 bg-orange-50 border-orange-100',
                    'approved' => $leave->is_paid ? 'text-green-600 bg-green-50 border-green-100' : 'text-indigo-600 bg-indigo-50 border-indigo-100',
                    'rejected' => 'text-red-600 bg-red-50 border-red-100',
                    'cancelled' => 'text-gray-500 bg-gray-50 border-gray-100',
                ];
                $statusClass = $statusColors[$leave->status] ?? 'text-blue-600 bg-blue-50 border-blue-100';
                $recommendationStatus = $leave->status === 'rejected'
                    ? __('For disapproval')
                    : ($leave->chief_status === 'approved' || $leave->hrstaff_status === 'approved' || $leave->rd_status === 'approved' ? __('For approval') : __('Pending review'));
                $disapprovalReason = trim(collect([$leave->rd_remarks, $leave->hrstaff_remarks, $leave->chief_remarks, $leave->remarks])->filter()->implode(' '));
                $employeeName = trim(($employee?->lastname ?? '') . ', ' . ($employee?->firstname ?? '') . ' ' . ($employee?->middlename ? \Illuminate\Support\Str::substr($employee->middlename, 0, 1) . '.' : ''));
            @endphp

            <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white p-8 shadow-sm md:p-10">
                <div class="flex flex-col gap-6 border-b border-gray-100 pb-8 md:flex-row md:items-start md:justify-between">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900">{{ trim(($employee?->firstname ?? '') . ' ' . ($employee?->lastname ?? '')) ?: __('Employee') }}</h1>
                        <p class="mt-1 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                            {{ $employee?->position ?: __('No position') }}
                            <span class="mx-1 text-gray-300">|</span>
                            {{ $employee?->division ?: __('No division') }}
                        </p>
                    </div>
                    <span class="rounded-full border px-4 py-2 text-[10px] font-black uppercase tracking-[0.18em] {{ $statusClass }}">
                        {{ $leave->status_label }}
                    </span>
                </div>

                <section class="mt-8">
                    <div class="mb-4 border-b border-gray-100 pb-2 text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500">
                        {{ __('1-5 Applicant and Filing Information') }}
                    </div>
                    <dl class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2">
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('1. Office / Department') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">{{ $employee?->division ?: 'DMW RO XI' }}</dd>
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('2. Name') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">{{ $employeeName ?: __('N/A') }}</dd>
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('3. Date of Filing') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">{{ \Carbon\Carbon::parse($leave->date_filed)->format('F d, Y h:i A') }}</dd>
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('4. Position') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">{{ $employee?->position ?: __('N/A') }}</dd>
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('5. Salary') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">{{ $employee?->salary ?? __('N/A') }}</dd>
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('Requested Pay Option') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">{{ $leave->is_paid ? __('With Pay') : __('Without Pay') }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="mt-8">
                    <div class="mb-4 border-b border-gray-100 pb-2 text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500">
                        {{ __('6. Details of Application') }}
                    </div>
                    <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2">
                        <div class="group relative rounded-xl border border-indigo-100 bg-indigo-50/40 px-4 py-3 md:col-span-2" tabindex="0" @if($leave->leaveType?->legal_basis) title="{{ $leave->leaveType->legal_basis }}" @endif>
                            <div class="flex items-center gap-2">
                                <dt class="font-bold text-indigo-500">{{ __('6.A Type of Leave to be Availed Of') }}</dt>
                            </div>
                            <dd class="mt-1 text-2xl font-black text-gray-900">{{ $leaveType }}</dd>
                            @if($leave->leaveType?->legal_basis)
                                <div class="pointer-events-none absolute left-4 top-full z-20 mt-2 hidden w-[min(28rem,calc(100vw-3rem))] rounded-xl border border-gray-100 bg-white p-4 text-xs font-medium leading-relaxed text-gray-600 shadow-xl group-hover:block group-focus:block">
                                    <span class="block text-[10px] font-black uppercase tracking-[0.18em] text-indigo-500">{{ __('Legal Basis') }}</span>
                                    <span class="mt-1 block">{{ $leave->leaveType->legal_basis }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('6.B Details of Leave') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">{{ $detailContext }}</dd>
                            <dd class="mt-1 font-medium leading-relaxed text-gray-800">{{ $reason ?: __('N/A') }}</dd>
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('6.C Number of Working Days Applied For') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">{{ $duration }} {{ \Illuminate\Support\Str::plural('day', $duration) }}</dd>
                            <dd class="mt-1 font-medium text-gray-700">{{ __('Inclusive Dates') }}: {{ $inclusiveDates }}</dd>
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('6.D Commutation') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">{{ __('Requested') }}</dd>
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('Supporting Document') }}</dt>
                            @if($leave->attachment_path)
                                <dd class="mt-1 flex items-center justify-between gap-3">
                                    <span class="truncate font-black text-gray-800">{{ basename($leave->attachment_path) }}</span>
                                    <a href="{{ asset('storage/' . $leave->attachment_path) }}" target="_blank" class="shrink-0 rounded-lg bg-indigo-600 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-white transition hover:bg-indigo-700">
                                        {{ __('View') }}
                                    </a>
                                </dd>
                            @else
                                <dd class="mt-1 font-black text-gray-800">{{ __('N/A') }}</dd>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="mt-8">
                    <div class="mb-4 border-b border-gray-100 pb-2 text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500">
                        {{ __('7. Details of Action on Application') }}
                    </div>
                    <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2">
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('7.A Certification of Leave Credits') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">{{ __('As of') }} {{ now()->format('F d, Y') }}</dd>
                            <dd class="mt-1 text-gray-700">
                                {{ __('Current balance') }}:
                                <span class="font-black text-gray-800">
                                    {{ isset($leaveCredit) ? number_format($leaveCredit?->balance ?? 0, 1) . ' ' . \Illuminate\Support\Str::plural('day', $leaveCredit?->balance ?? 0) : __('N/A') }}
                                </span>
                            </dd>
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('7.B Recommendation') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">{{ $recommendationStatus }}</dd>
                            <dd class="mt-1 text-gray-700">{{ __('HR') }}: {{ ucfirst($leave->hrstaff_status ?: 'pending') }} | {{ __('Chief') }}: {{ ucfirst($leave->chief_status ?: 'pending') }} | {{ __('Regional Director') }}: {{ ucfirst($leave->rd_status ?: 'pending') }}</dd>
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('7.C Approved For') }}</dt>
                            <dd class="mt-1 font-black text-gray-800">
                                @if($leave->status === 'approved')
                                    {{ $duration }} {{ \Illuminate\Support\Str::plural('day', $duration) }} {{ $leave->is_paid ? __('with pay') : __('without pay') }}
                                @else
                                    {{ __('Pending final approval') }}
                                @endif
                            </dd>
                        </div>
                        <div class="rounded-xl bg-gray-50/70 px-4 py-3">
                            <dt class="font-bold text-gray-400">{{ __('7.D Disapproved Due To') }}</dt>
                            <dd class="mt-1 font-medium leading-relaxed text-gray-800">{{ $leave->status === 'rejected' ? ($disapprovalReason ?: __('No remarks provided.')) : __('N/A') }}</dd>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
