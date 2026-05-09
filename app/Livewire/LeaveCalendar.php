<?php

namespace App\Livewire;

use App\Models\LeaveRequest;
use Carbon\Carbon;
use Livewire\Component;

class LeaveCalendar extends Component
{
    public $selectedMonth;
    public $month;
    public $year;

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->format('Y-m');
        $this->updateDates();
    }

    public function updatedSelectedMonth()
    {
        $this->updateDates();
    }

    protected function updateDates()
    {
        $date = Carbon::parse($this->selectedMonth . '-01');
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function render()
    {
        $startOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        $daysInMonth = $startOfMonth->daysInMonth;
        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)

        $leaves = LeaveRequest::with(['employee', 'leaveType'])
            ->where('status', 'approved')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                      ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                          $q->where('start_date', '<=', $startOfMonth)
                            ->where('end_date', '>=', $endOfMonth);
                      });
            })
            ->get();

        $calendarDays = [];
        
        // Add empty days for the start
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $calendarDays[] = [
                'day' => null,
                'leaves' => collect(),
            ];
        }

        // Add actual days
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::createFromDate($this->year, $this->month, $day)->startOfDay();
            
            $dayLeaves = $leaves->filter(function ($leave) use ($currentDate) {
                return $currentDate->between(
                    Carbon::parse($leave->start_date)->startOfDay(),
                    Carbon::parse($leave->end_date)->endOfDay()
                );
            });

            $calendarDays[] = [
                'day' => $day,
                'date' => $currentDate,
                'isToday' => $currentDate->isToday(),
                'leaves' => $dayLeaves,
            ];
        }

        $leaveTypes = \App\Models\LeaveType::all();
        $colors = [
            ['pill' => 'bg-red-50 text-red-600 border-red-100', 'dot' => 'bg-red-500'],
            ['pill' => 'bg-green-50 text-green-600 border-green-100', 'dot' => 'bg-green-500'],
            ['pill' => 'bg-blue-50 text-blue-600 border-blue-100', 'dot' => 'bg-blue-500'],
            ['pill' => 'bg-indigo-50 text-indigo-600 border-indigo-100', 'dot' => 'bg-indigo-500'],
            ['pill' => 'bg-purple-50 text-purple-600 border-purple-100', 'dot' => 'bg-purple-500'],
            ['pill' => 'bg-pink-50 text-pink-600 border-pink-100', 'dot' => 'bg-pink-500'],
            ['pill' => 'bg-orange-50 text-orange-600 border-orange-100', 'dot' => 'bg-orange-500'],
        ];

        $leaveTypeColors = [];
        foreach ($leaveTypes as $index => $type) {
            $leaveTypeColors[$type->name] = $colors[$index % count($colors)];
        }

        return view('livewire.leave-calendar', [
            'calendarDays' => $calendarDays,
            'monthName' => $startOfMonth->format('F'),
            'year' => $this->year,
            'leaveTypes' => $leaveTypes,
            'leaveTypeColors' => $leaveTypeColors,
        ]);
    }
}
