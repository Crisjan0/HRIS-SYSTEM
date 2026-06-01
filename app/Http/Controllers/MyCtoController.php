<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MyCtoController extends Controller
{
    /**
     * Display the employee's compensatory time-off records.
     */
    public function index(): View
    {
        $employee = auth()->user()->employee;
        if (! $employee) {
            abort(404, 'Employee record not found.');
        }

        return view('my-cto.index');
    }
}
