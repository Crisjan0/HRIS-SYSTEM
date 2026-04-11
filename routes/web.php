<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DtrController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\MyLeaveController;
use App\Http\Controllers\PdsController;
use App\Http\Controllers\ProfileController;
use App\Models\Announcement;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $latestAnnouncement = Announcement::published()->latest()->first();

    return view('dashboard', compact('latestAnnouncement'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('employees', EmployeeController::class)->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::resource('leave-types', LeaveTypeController::class)->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::resource('holidays', HolidayController::class)->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::resource('leaves', MyLeaveController::class);
    Route::get('leave-applications/all', [LeaveApplicationController::class, 'all'])->name('leave-applications.all')->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::get('/leave-calendar', function () {
        return view('leaves.calendar');
    })->name('leave-calendar')->middleware('role:ADMIN,HRSTAFF,DIRECTOR,CHIEF,REGIONALDIRECTOR,REGIONAL DIRECTOR');
    Route::resource('leave-applications', LeaveApplicationController::class)->only(['index', 'update', 'show'])->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::get('/pds', [PdsController::class, 'index'])->name('pds.index');
    Route::get('/pds/edit', [PdsController::class, 'edit'])->name('pds.edit');
    Route::put('/pds', [PdsController::class, 'update'])->name('pds.update');
    Route::get('/view-announcements', [AnnouncementController::class, 'userIndex'])->name('announcements.view');
    Route::resource('announcements', AnnouncementController::class)->except(['show'])->middleware('role:ADMIN,HRSTAFF');
    Route::resource('announcements', AnnouncementController::class)->only(['show']);
    Route::get('my-dtr', [DtrController::class, 'myDtr'])->name('my-dtr.index');
    Route::resource('dtr', DtrController::class)->only(['index', 'show']);
    Route::post('/dtr/import', [DtrController::class, 'syncFromFile'])->name('dtr.import');
});

require __DIR__.'/auth.php';
