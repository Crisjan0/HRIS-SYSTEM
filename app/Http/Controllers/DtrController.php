<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DtrRecord;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendanceImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class DtrController extends Controller
{
    public function index(): View
    {
        $query = DtrRecord::with('employee')->latest();

        $userRole = strtoupper(auth()->user()->role);
        $adminRoles = ['ADMIN', 'HRSTAFF', 'DIRECTOR', 'CHIEF', 'REGIONALDIRECTOR', 'REGIONAL DIRECTOR'];

        // If not in administrative roles, only show their own records
        if (!in_array($userRole, $adminRoles)) {
            $employee = auth()->user()->employee;
            if ($employee) {
                $query->where('employee_id', $employee->id);
            } else {
                $query->where('employee_id', 0);
            }
        }

        $records = $query->paginate(15);

        return view('dtr.index', compact('records'));
    }

    public function myDtr(): View
    {
        $query = DtrRecord::with('employee')->latest();
        
        $employee = auth()->user()->employee;
        if ($employee) {
            $query->where('employee_id', $employee->id);
        } else {
            $query->where('employee_id', 0);
        }

        $records = $query->paginate(15);
        $isPersonal = true;

        return view('dtr.index', compact('records', 'isPersonal'));
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
