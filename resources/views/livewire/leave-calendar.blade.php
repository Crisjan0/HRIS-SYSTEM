<div class="p-6 lg:p-8 bg-white/50 backdrop-blur-xl rounded-[2.5rem] border border-white/40 shadow-2xl shadow-gray-200/50">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight uppercase tracking-widest">
                {{ $monthName }} {{ $year }}
            </h1>
            <p class="text-gray-500 font-medium mt-1">Approved employee leaves summary</p>
        </div>

        <div class="flex items-center gap-4 bg-white/80 p-2 rounded-2xl border border-gray-100 shadow-sm">
            <label for="month-picker" class="text-xs font-black text-gray-400 uppercase tracking-widest pl-2">Select Month:</label>
            <input 
                type="month" 
                id="month-picker" 
                wire:model.live="selectedMonth"
                class="border-0 bg-transparent text-sm font-bold text-gray-900 focus:ring-0 cursor-pointer"
            >
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
        <!-- Weekday Headers -->
        <div class="grid grid-cols-7 border-b border-gray-50 text-center">
            @foreach(['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'] as $day)
                <div class="py-4 text-[10px] font-black text-gray-400 tracking-widest border-r border-gray-50 last:border-r-0">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <!-- Days Grid -->
        <div class="grid grid-cols-7 auto-rows-[120px]">
            @foreach($calendarDays as $cell)
                <div class="relative p-2 border-r border-b border-gray-50 last:border-r-0 h-full overflow-hidden {{ !$cell['day'] ? 'bg-gray-50/30' : '' }}">
                    @if($cell['day'])
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-bold {{ $cell['isToday'] ? 'bg-indigo-600 text-white w-7 h-7 flex items-center justify-center rounded-full shadow-lg shadow-indigo-100' : 'text-gray-400' }}">
                                {{ $cell['day'] }}
                            </span>
                        </div>

                        <!-- Leave Pills -->
                        <div class="space-y-1">
                            @foreach($cell['leaves'] as $leave)
                                @php
                                    $colorData = $leaveTypeColors[$leave->leaveType->name] ?? ['pill' => 'bg-gray-50 text-gray-600 border-gray-100', 'dot' => 'bg-gray-400'];
                                    $colorClass = $colorData['pill'];
                                @endphp
                                <div class="px-2 py-1 rounded-md text-[9px] font-bold border truncate hover:scale-105 transition-transform cursor-pointer {{ $colorClass }}" title="{{ $leave->employee->firstname }} {{ $leave->employee->lastname }} - {{ $leave->leaveType->name }}">
                                    {{ $leave->employee->lastname }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
            
            <!-- Fill remaining grid cells if needed -->
            @php $remainingCells = 7 - (count($calendarDays) % 7); @endphp
            @if($remainingCells < 7 && count($calendarDays) > 0)
                @for($i = 0; $i < $remainingCells; $i++)
                    <div class="bg-gray-50/30 border-r border-b border-gray-50 last:border-r-0"></div>
                @endfor
            @endif
        </div>
    </div>

    <!-- Legend Section -->
    <div class="mt-8 flex flex-wrap gap-x-8 gap-y-4 px-6 py-4 bg-white/60 backdrop-blur-sm rounded-2xl border border-gray-100">
        @foreach($leaveTypes as $type)
            @php
                $colorData = $leaveTypeColors[$type->name] ?? ['pill' => '', 'dot' => 'bg-gray-400'];
            @endphp
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full {{ $colorData['dot'] }} shadow-sm shadow-gray-200"></span>
                <span class="text-xs font-bold text-gray-600">{{ $type->name }}</span>
            </div>
        @endforeach
    </div>
</div>
