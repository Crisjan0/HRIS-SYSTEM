<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DtrRecord;
use Carbon\Carbon;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendanceImport;
use Illuminate\Http\RedirectResponse;

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
            // HR/Admin Daily Attendance Monitoring logic
            $selectedDate = $request->query('date', now()->format('Y-m-d'));
            try {
                Carbon::parse($selectedDate);
            } catch (\Exception $e) {
                $selectedDate = now()->format('Y-m-d');
            }

            $search = $request->query('search');
            $divisionFilter = $request->query('division', 'all');
            $statusFilter = strtolower($request->query('status', 'all'));

            // Fetch active employees
            $employeesQuery = \App\Models\Employee::query();

            if ($search) {
                $employeesQuery->where(function ($q) use ($search) {
                    $q->where('firstname', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%")
                      ->orWhere('rfid_number', 'like', "%{$search}%");
                });
            }

            if ($divisionFilter && $divisionFilter !== 'all') {
                $employeesQuery->where('division', $divisionFilter);
            }

            $allEmployees = $employeesQuery->get();

            // Fetch all unique divisions for filter
            $divisions = \App\Models\Employee::whereNotNull('division')
                ->where('division', '!=', '')
                ->distinct()
                ->orderBy('division')
                ->pluck('division');

            // Fetch DTR records for the selected date
            $dtrRecords = DtrRecord::whereDate('date', $selectedDate)->get()->groupBy('employee_id');

            // Stats counts
            $stats = [
                'scanned' => 0,
                'present' => 0,
                'late' => 0,
                'in_office' => 0,
                'completed' => 0,
                'no_record' => 0,
                'total' => $allEmployees->count()
            ];

            $processed = collect();
            foreach ($allEmployees as $emp) {
                $record = $dtrRecords->get($emp->id)?->first();

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

                $empData = [
                    'employee' => $emp,
                    'time_in' => $timeIn,
                    'time_out' => $timeOut,
                    'status' => $status,
                    'remarks' => $remarks,
                    'hours_worked' => $hoursWorked,
                    'late_minutes' => $lateMinutes,
                    'undertime_minutes' => $undertimeMinutes,
                ];

                $processed->push($empData);
            }

            // Apply status filter
            if ($statusFilter && $statusFilter !== 'all') {
                $processed = $processed->filter(function ($item) use ($statusFilter) {
                    return strtolower($item['status']) === $statusFilter;
                });
            }

            // Sort: arrival time (time_in ASC) first, no-time_in last
            $processed = $processed->sort(function ($a, $b) {
                $timeA = $a['time_in'];
                $timeB = $b['time_in'];

                if ($timeA && $timeB) {
                    return strcmp($timeA, $timeB);
                }
                if ($timeA) return -1;
                if ($timeB) return 1;
                return 0;
            })->values();

            // Export to CSV if requested
            if ($request->query('export') === 'csv') {
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="daily_attendance_' . $selectedDate . '.csv"',
                ];
                
                $callback = function() use ($processed, $selectedDate) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['Queue No', 'Employee Name', 'Employee No', 'Division', 'First In', 'Lunch Out', 'Lunch In', 'Last Out', 'Actual Hours Worked', 'Late Minutes', 'Undertime Minutes', 'Status', 'Remarks']);
                    
                    $queue = 1;
                    foreach ($processed as $item) {
                        $emp = $item['employee'];
                        fputcsv($file, [
                            $item['time_in'] ? $queue++ : '—',
                            $emp->firstname . ' ' . $emp->lastname,
                            $emp->employee_no ?? ('EMP-' . str_pad((string) $emp->id, 4, '0', STR_PAD_LEFT)),
                            $emp->division ?? '—',
                            $item['time_in'] ? Carbon::parse($item['time_in'])->format('h:i A') : '—',
                            '—',
                            '—',
                            $item['time_out'] ? Carbon::parse($item['time_out'])->format('h:i A') : '—',
                            $item['hours_worked'],
                            $item['late_minutes'],
                            $item['undertime_minutes'],
                            $item['status'],
                            $item['remarks'],
                        ]);
                    }
                    fclose($file);
                };
                
                return response()->stream($callback, 200, $headers);
            }

            // Paginate
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $perPage = 20;
            $currentPageItems = $processed->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $paginatedRecords = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentPageItems,
                $processed->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            $isPersonal = false;

            return view('dtr.hrview', compact('paginatedRecords', 'isPersonal', 'selectedDate', 'stats', 'divisions', 'divisionFilter', 'statusFilter', 'search'));
        }

        // Existing personal/non-admin monthly DTR view logic
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

        if (!file_exists($absPath)) {
            return back()->with('error', 'Biometric file not found in: ' . $absPath);
        }

        try {
            Excel::import(new AttendanceImport, $absPath);
            return back()->with('success', 'Attendance records synced successfully from biometric file.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error syncing file: ' . $e->getMessage());
        }
    }
}
