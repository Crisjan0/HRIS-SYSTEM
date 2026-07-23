@php
    $isHrAdmin = $isHrAdmin ?? in_array(strtolower(auth()->user()->role ?? ''), ['admin', 'hrstaff'], true);
@endphp
<div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 p-6">
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h4 class="text-xs font-black uppercase tracking-widest text-gray-400">{{ $isHrAdmin ? __('Holiday Calendar') : __('My Leave Calendar') }}</h4>
            <p class="text-lg font-black text-gray-900 uppercase tracking-tight">{{ $leaveCalendarMonth->format('F Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard', ['month' => $previousLeaveCalendarMonth]) }}" data-calendar-link data-calendar-url="{{ route('dashboard.leave-calendar', ['month' => $previousLeaveCalendarMonth]) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-blue-900 shadow-sm transition hover:border-indigo-200 hover:text-indigo-600" title="{{ __('Previous month') }}" aria-label="{{ __('Previous month') }}">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <a href="{{ route('dashboard', ['month' => $nextLeaveCalendarMonth]) }}" data-calendar-link data-calendar-url="{{ route('dashboard.leave-calendar', ['month' => $nextLeaveCalendarMonth]) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-blue-900 shadow-sm transition hover:border-indigo-200 hover:text-indigo-600" title="{{ __('Next month') }}" aria-label="{{ __('Next month') }}">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-7 rounded-2xl border border-gray-100 bg-gray-50/60 p-2 text-center">
        @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $weekday)
            <div class="py-1 text-[10px] font-black uppercase tracking-widest text-gray-400">{{ $weekday }}</div>
        @endforeach

        @foreach($leaveCalendarDays as $cell)
            @php
                $requests = $cell['requests'] ?? collect();
                $hasLeave = $requests->isNotEmpty();
                $isApproved = $hasLeave && $requests->contains(fn ($leave) => $leave->status === 'approved');
                $hasPending = $hasLeave && $requests->contains(fn ($leave) => $leave->status !== 'approved');
                $isHoliday = (bool) ($cell['is_holiday'] ?? false);
                $holidayName = $cell['holiday_name'] ?? null;
                $titleParts = collect();

                if ($holidayName) {
                    $titleParts->push($holidayName);
                }

                if ($hasLeave) {
                    $titleParts = $titleParts->merge($requests->map(fn ($leave) => ($leave->leaveType?->name ?? 'Leave').' - '.ucfirst($leave->status)));
                }

                $title = $titleParts->implode(', ');
            @endphp
            <div class="p-1">
                @if($cell['day'])
                    <div title="{{ $title }}" class="relative flex h-10 items-center justify-center rounded-xl text-xs font-bold transition {{ $isApproved ? 'border border-green-200 bg-green-50 text-green-700 shadow-sm' : ($hasPending ? 'border border-amber-200 bg-amber-50 text-amber-700 shadow-sm' : ($isHoliday ? 'border border-red-100 bg-red-50 text-red-600' : ($cell['is_today'] ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : ($cell['is_weekend'] ? 'text-gray-300' : 'text-gray-600 hover:bg-white')))) }}">
                        {{ $cell['day'] }}
                    </div>
                @else
                    <div class="h-10"></div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-4 flex flex-wrap items-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-400">
        @if(!$isHrAdmin)
            <span class="inline-flex items-center gap-1.5">
                <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                {{ __('Approved Leave') }}
            </span>
            <span class="inline-flex items-center gap-1.5">
                <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                {{ __('Pending Leave') }}
            </span>
        @endif
        <span class="inline-flex items-center gap-1.5">
            <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
            {{ __('Holiday') }}
        </span>
    </div>

    @if(!$isHrAdmin && $leaveUpcomingRequests->isNotEmpty())
        <div class="mt-4 space-y-2">
            @foreach($leaveUpcomingRequests as $leave)
                <div class="flex items-center justify-between gap-3 rounded-xl bg-gray-50 px-3 py-2">
                    <div class="min-w-0">
                        <p class="truncate text-xs font-black text-gray-800">{{ $leave->leaveType?->name ?? __('Leave') }}</p>
                        <p class="text-[10px] font-semibold text-gray-400">
                            {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                        </p>
                    </div>
                    <span class="shrink-0 rounded-full px-2 py-1 text-[9px] font-black uppercase tracking-widest {{ $leave->status === 'approved' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                        {{ $leave->status }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>
