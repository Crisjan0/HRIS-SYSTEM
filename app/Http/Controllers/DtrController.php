<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceImport;
use App\Models\DtrRecord;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class DtrController extends Controller
{
    public function index(Request $request): View
    {
        return $this->dtrView($request, false);
    }

    public function myDtr(Request $request): View
    {
        return $this->dtrView($request, true);
    }

    private function dtrView(Request $request, bool $personalOnly): View
    {
        $userRole = strtoupper(auth()->user()->role);
        $adminRoles = ['ADMIN', 'HRSTAFF', 'CHIEF', 'REGIONALDIRECTOR'];
        $isAdministrativeDtr = ! $personalOnly && in_array($userRole, $adminRoles, true);

        if ($isAdministrativeDtr) {
            $selectedDate = $request->query('date', now()->format('Y-m-d'));

            try {
                Carbon::parse($selectedDate);
            } catch (\Throwable $exception) {
                $selectedDate = now()->format('Y-m-d');
            }

            $search = trim((string) $request->query('search', ''));
            $divisionFilter = (string) $request->query('division', 'all');
            $employmentStatusFilter = (string) $request->query('employment_status', 'all');

            $employeesQuery = \App\Models\Employee::query();

            if ($search !== '') {
                $employeesQuery->where(function ($query) use ($search) {
                    $query->where('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('division', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%")
                        ->orWhere('employment_status', 'like', "%{$search}%")
                        ->orWhere('rfid_number', 'like', "%{$search}%");
                });
            }

            if ($divisionFilter !== 'all') {
                $employeesQuery->where('division', $divisionFilter);
            }

            if ($employmentStatusFilter !== 'all') {
                $employeesQuery->where('employment_status', $employmentStatusFilter);
            }

            $employees = $employeesQuery->orderBy('lastname')->orderBy('firstname')->get();

            $divisions = \App\Models\Employee::whereNotNull('division')
                ->where('division', '!=', '')
                ->distinct()
                ->orderBy('division')
                ->pluck('division');

            $employmentStatuses = \App\Models\Employee::whereNotNull('employment_status')
                ->where('employment_status', '!=', '')
                ->distinct()
                ->orderBy('employment_status')
                ->pluck('employment_status');

            $dtrRecords = DtrRecord::whereDate('date', $selectedDate)->get()->groupBy('employee_id');

            $stats = [
                'scanned' => 0,
                'present' => 0,
                'late' => 0,
                'in_office' => 0,
                'completed' => 0,
                'no_record' => 0,
                'total' => $employees->count(),
            ];

            $attendanceRecords = $employees->map(function ($employee) use ($dtrRecords, &$stats) {
                $record = $dtrRecords->get($employee->id)?->first();

                $timeIn = $record?->time_in;
                $timeOut = $record?->time_out;
                $remarks = $record?->status ?? '';

                $status = 'Not Yet In';
                $lateMinutes = 0;
                $undertimeMinutes = 0;
                $hoursWorked = '0.00';

                if ($timeIn) {
                    $stats['scanned']++;
                    $carbonIn = Carbon::parse($timeIn);

                    if ($carbonIn->format('H:i:s') > '08:00:00') {
                        $lateMinutes = $carbonIn->diffInMinutes(Carbon::parse('08:00:00'));
                        $status = 'Late';
                        $stats['late']++;
                    } else {
                        $status = 'In Office';
                        $stats['present']++;
                    }

                    if ($timeOut) {
                        $status = 'Completed';
                        $carbonOut = Carbon::parse($timeOut);

                        if ($carbonOut->format('H:i:s') < '17:00:00') {
                            $undertimeMinutes = Carbon::parse('17:00:00')->diffInMinutes($carbonOut);
                        }

                        $minutes = $carbonIn->diffInMinutes($carbonOut);
                        $hoursWorked = number_format($minutes / 60, 2);
                        $stats['completed']++;
                    } else {
                        $stats['in_office']++;
                    }
                } else {
                    $stats['no_record']++;
                }

                return [
                    'employee' => $employee,
                    'time_in' => $timeIn,
                    'time_out' => $timeOut,
                    'status' => $status,
                    'remarks' => $remarks,
                    'hours_worked' => $hoursWorked,
                    'late_minutes' => $lateMinutes,
                    'undertime_minutes' => $undertimeMinutes,
                ];
            })->sort(function (array $left, array $right) {
                $timeA = $left['time_in'];
                $timeB = $right['time_in'];

                if ($timeA && $timeB) {
                    return strcmp($timeA, $timeB);
                }

                if ($timeA) {
                    return -1;
                }

                if ($timeB) {
                    return 1;
                }

                return strcmp(
                    strtolower($left['employee']->lastname . ' ' . $left['employee']->firstname),
                    strtolower($right['employee']->lastname . ' ' . $right['employee']->firstname)
                );
            })->values();

            if ($request->query('export') === 'csv') {
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="daily_attendance_' . $selectedDate . '.csv"',
                ];

                $callback = function () use ($attendanceRecords) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['Employee Name', 'Employee No', 'Department', 'Position', 'Employment Status', 'First In', 'Last Out', 'Actual Hours Worked', 'Late Minutes', 'Undertime Minutes', 'Status', 'Remarks']);

                    foreach ($attendanceRecords as $item) {
                        $employee = $item['employee'];

                        fputcsv($file, [
                            trim(($employee->firstname ?? '') . ' ' . ($employee->lastname ?? '')),
                            $employee->employee_no ?? ('EMP-' . str_pad((string) $employee->id, 4, '0', STR_PAD_LEFT)),
                            $employee->division ?? 'N/A',
                            $employee->position ?? 'N/A',
                            $employee->employment_status ?? 'N/A',
                            $item['time_in'] ? Carbon::parse($item['time_in'])->format('h:i A') : 'N/A',
                            $item['time_out'] ? Carbon::parse($item['time_out'])->format('h:i A') : 'N/A',
                            $item['hours_worked'],
                            $item['late_minutes'] ?: 0,
                            $item['undertime_minutes'] ?: 0,
                            $item['status'],
                            $item['remarks'] ?: 'N/A',
                        ]);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            $isPersonal = false;

            return view('dtr.hrview', compact(
                'attendanceRecords',
                'isPersonal',
                'selectedDate',
                'stats',
                'divisions',
                'divisionFilter',
                'employmentStatuses',
                'employmentStatusFilter',
                'search'
            ));
        }

        $selectedMonth = (int) $request->query('month', now()->month);
        $selectedYear = (int) $request->query('year', now()->year);
        $selectedMonth = $selectedMonth >= 1 && $selectedMonth <= 12 ? $selectedMonth : now()->month;
        $selectedYear = $selectedYear >= 2000 && $selectedYear <= 2100 ? $selectedYear : now()->year;

        $query = DtrRecord::with('employee')
            ->whereMonth('date', $selectedMonth)
            ->whereYear('date', $selectedYear);

        if ($personalOnly || ! in_array($userRole, $adminRoles, true)) {
            $employee = auth()->user()->employee;

            if ($employee) {
                $query->where('employee_id', $employee->id);
            } else {
                $query->where('employee_id', 0);
            }
        }

        $dtrRecords = $query->orderBy('date')->orderBy('time_in')->get();

        $actualMinutes = $dtrRecords->sum(function ($record) {
            if (! $record->time_in || ! $record->time_out) {
                return 0;
            }

            return Carbon::parse($record->time_in)->diffInMinutes(Carbon::parse($record->time_out));
        });

        $lateRecords = $dtrRecords
            ->filter(fn ($record) => strtolower((string) $record->status) === 'late')
            ->values();

        $selectedDate = Carbon::create($selectedYear, $selectedMonth, 1);
        $period = $selectedDate->format('F j') . ' to ' . $selectedDate->copy()->endOfMonth()->format('j, Y');
        $dtrSummary = [
            'month' => $selectedDate->format('F'),
            'year' => $selectedYear,
            'period' => $period,
            'records' => $dtrRecords->count(),
            'present' => $dtrRecords->where('status', 'Present')->count(),
            'late' => $lateRecords->count(),
            'absent' => $dtrRecords->where('status', 'Absent')->count(),
            'actual_hours' => number_format($actualMinutes / 60, 2),
            'employee_no' => auth()->user()->employee?->id ?? 'N/A',
        ];

        $isPersonal = $personalOnly;

        return view('dtr.index', compact('dtrRecords', 'isPersonal', 'selectedMonth', 'selectedYear', 'dtrSummary', 'lateRecords'));
    }

    public function syncFromFile(): RedirectResponse
    {
        $absPath = storage_path('app/attendance/dtr.xlsx');

        if (! file_exists($absPath)) {
            return back()->with('error', 'Biometric file not found in: ' . $absPath);
        }

        try {
            Excel::import(new AttendanceImport, $absPath);

            return back()->with('success', 'Attendance records synced successfully from biometric file.');
        } catch (\Throwable $exception) {
            return back()->with('error', 'Error syncing file: ' . $exception->getMessage());
        }
    }
}
