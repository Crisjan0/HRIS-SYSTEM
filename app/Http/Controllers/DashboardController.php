<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $latestAnnouncement = Announcement::published()->latest()->first();
        $employee = $request->user()->employee;
        $calendar = $this->leaveCalendarData($request);

        return view('dashboard', array_merge([
            'latestAnnouncement' => $latestAnnouncement,
            'employee' => $employee,
        ], $calendar));
    }

    public function leaveCalendar(Request $request): JsonResponse
    {
        $calendar = $this->leaveCalendarData($request);

        return response()->json([
            'html' => view('dashboard._leave-calendar', $calendar)->render(),
            'month' => $calendar['leaveCalendarMonth']->format('Y-m'),
        ]);
    }

    private function leaveCalendarData(Request $request): array
    {
        $employee = $request->user()->employee;
        $requestedMonth = $request->query('month');
        $leaveCalendarMonth = is_string($requestedMonth) && preg_match('/^\d{4}-\d{2}$/', $requestedMonth)
            ? Carbon::createFromFormat('Y-m', $requestedMonth)->startOfMonth()
            : now()->startOfMonth();
        $leaveCalendarStart = $leaveCalendarMonth->copy()->startOfMonth();
        $leaveCalendarEnd = $leaveCalendarMonth->copy()->endOfMonth();
        $previousLeaveCalendarMonth = $leaveCalendarMonth->copy()->subMonth()->format('Y-m');
        $nextLeaveCalendarMonth = $leaveCalendarMonth->copy()->addMonth()->format('Y-m');
        $leaveCalendarRequests = collect();
        $leaveCalendarDays = collect();
        $leaveUpcomingRequests = collect();

        if ($employee) {
            $leaveCalendarRequests = $employee->leaveRequests()
                ->with('leaveType')
                ->whereNotIn('status', ['rejected', 'cancelled'])
                ->where(function ($query) use ($leaveCalendarStart, $leaveCalendarEnd) {
                    $query->whereBetween('start_date', [$leaveCalendarStart, $leaveCalendarEnd])
                        ->orWhereBetween('end_date', [$leaveCalendarStart, $leaveCalendarEnd])
                        ->orWhere(function ($innerQuery) use ($leaveCalendarStart, $leaveCalendarEnd) {
                            $innerQuery->where('start_date', '<=', $leaveCalendarStart)
                                ->where('end_date', '>=', $leaveCalendarEnd);
                        });
                })
                ->get();

            $holidays = Holiday::whereBetween('date', [$leaveCalendarStart, $leaveCalendarEnd])
                ->get()
                ->keyBy(fn ($holiday) => $holiday->date->format('Y-m-d'));

            for ($i = 0; $i < $leaveCalendarStart->dayOfWeek; $i++) {
                $leaveCalendarDays->push([
                    'day' => null,
                    'date' => null,
                    'requests' => collect(),
                    'is_today' => false,
                    'is_weekend' => false,
                    'is_holiday' => false,
                    'holiday_name' => null,
                ]);
            }

            for ($day = 1; $day <= $leaveCalendarStart->daysInMonth; $day++) {
                $date = $leaveCalendarMonth->copy()->day($day)->startOfDay();
                $dateKey = $date->format('Y-m-d');
                $holiday = $holidays->get($dateKey);
                $requests = collect();

                if ($date->isWeekday() && ! $holiday) {
                    $requests = $leaveCalendarRequests->filter(function ($leave) use ($date) {
                        return $date->between(
                            Carbon::parse($leave->start_date)->startOfDay(),
                            Carbon::parse($leave->end_date)->endOfDay()
                        );
                    })->values();
                }

                $leaveCalendarDays->push([
                    'day' => $day,
                    'date' => $date,
                    'requests' => $requests,
                    'is_today' => $date->isToday(),
                    'is_weekend' => $date->isWeekend(),
                    'is_holiday' => (bool) $holiday,
                    'holiday_name' => $holiday?->name,
                ]);
            }

            $leaveUpcomingRequests = $employee->leaveRequests()
                ->with('leaveType')
                ->whereNotIn('status', ['rejected', 'cancelled'])
                ->whereDate('end_date', '>=', now()->startOfDay())
                ->orderBy('start_date')
                ->limit(3)
                ->get();
        }

        return compact(
            'employee',
            'leaveCalendarMonth',
            'previousLeaveCalendarMonth',
            'nextLeaveCalendarMonth',
            'leaveCalendarDays',
            'leaveUpcomingRequests'
        );
    }
}
