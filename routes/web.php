<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DtrController;
use App\Http\Controllers\EmployeeAccountController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LocatorSlipController;
use App\Http\Controllers\CtoController;
use App\Http\Controllers\MyCtoController;
use App\Http\Controllers\MyLeaveController;
use App\Http\Controllers\PdsController;
use App\Http\Controllers\PdsSectionReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalnController;
use App\Http\Controllers\TravelOrderController;
use App\Models\Announcement;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/personal-information', [EmployeeController::class, 'myRecord'])->name('personal-information.show');
    Route::get('/personal-information/edit', [EmployeeController::class, 'editMyRecord'])->name('personal-information.edit');
    Route::patch('/personal-information', [EmployeeController::class, 'updateMyRecord'])->name('personal-information.update');
});

Route::get('/dashboard', function () {
    $latestAnnouncement = Announcement::published()->latest()->first();

    return view('dashboard', compact('latestAnnouncement'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/employees/{employee}/profile-picture', [EmployeeController::class, 'uploadProfilePicture'])->name('employees.profile-picture');
    Route::get('/locator-slips', [LocatorSlipController::class, 'index'])->name('locator-slips.index');
    Route::get('/locator-slips/create', [LocatorSlipController::class, 'create'])->name('locator-slips.create');
    Route::post('/locator-slips', [LocatorSlipController::class, 'store'])->name('locator-slips.store');
    Route::resource('employees', EmployeeController::class)->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::get('reports', function () {
        return view('reports.index');
    })->name('reports.index')->middleware('role:ADMIN,HRSTAFF');
    Route::get('employee-accounts', [EmployeeAccountController::class, 'index'])->name('employee-accounts.index')->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::get('employee-accounts/{user}', [EmployeeAccountController::class, 'show'])->name('employee-accounts.show')->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::patch('employee-accounts/{user}', [EmployeeAccountController::class, 'update'])->name('employee-accounts.update')->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::patch('employee-accounts/{user}/approve', [EmployeeAccountController::class, 'approve'])->name('employee-accounts.approve')->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::delete('employee-accounts/{user}/reject', [EmployeeAccountController::class, 'reject'])->name('employee-accounts.reject')->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::resource('leave-types', LeaveTypeController::class)->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::resource('holidays', HolidayController::class)->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::resource('leaves', MyLeaveController::class);
    Route::get('leave-applications/all', [LeaveApplicationController::class, 'all'])->name('leave-applications.all')->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::get('/leave-calendar', function () {
        return view('leaves.calendar');
    })->name('leave-calendar')->middleware('role:ADMIN,HRSTAFF,DIRECTOR,CHIEF,REGIONALDIRECTOR,REGIONAL DIRECTOR');
    Route::resource('leave-applications', LeaveApplicationController::class)->only(['index', 'update', 'show'])->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::get('/pds', [PdsController::class, 'index'])->name('pds.index');
    Route::get('/pds/download', [PdsController::class, 'download'])->name('pds.download');
    Route::get('/pds/edit', [PdsController::class, 'edit'])->name('pds.edit');
    Route::put('/pds', [PdsController::class, 'update'])->name('pds.update');
    Route::get('/view-announcements', [AnnouncementController::class, 'userIndex'])->name('announcements.view');
    Route::resource('announcements', AnnouncementController::class)->except(['show'])->middleware('role:ADMIN,HRSTAFF,REGIONALDIRECTOR,REGIONAL DIRECTOR');
    Route::resource('announcements', AnnouncementController::class)->only(['show']);
    Route::get('my-dtr', [DtrController::class, 'myDtr'])->name('my-dtr.index');
    Route::get('my-cto', [MyCtoController::class, 'index'])->name('my-cto.index');
    Route::get('my-cto/create', [MyCtoController::class, 'create'])->name('my-cto.create');
    Route::post('my-cto', [MyCtoController::class, 'store'])->name('my-cto.store');
    Route::get('my-cto/{ctoRequest}', [MyCtoController::class, 'show'])->name('my-cto.show');
    Route::resource('dtr', DtrController::class)->only(['index', 'show']);
    Route::post('/dtr/import', [DtrController::class, 'syncFromFile'])->name('dtr.import');
    Route::get('/hr/locator-slips', [LocatorSlipController::class, 'manageIndex'])->name('hr.locator-slips.index');
    Route::get('/hr/locator-slips/all', [LocatorSlipController::class, 'allIndex'])->name('hr.locator-slips.all');
    Route::get('/hr/locator-slips/pending', [LocatorSlipController::class, 'pendingIndex'])->name('hr.locator-slips.pending');
    Route::get('/hr/locator-slips/{locatorSlip}', [LocatorSlipController::class, 'hrShow'])->name('hr.locator-slips.show');
    Route::get('/locator-slips/{locatorSlip}', [LocatorSlipController::class, 'show'])->name('locator-slips.show');
    Route::patch('/locator-slips/{locatorSlip}/approve', [LocatorSlipController::class, 'approve'])->name('locator-slips.approve');
    Route::patch('/locator-slips/{locatorSlip}/reject', [LocatorSlipController::class, 'reject'])->name('locator-slips.reject');
    Route::get('/locator-slips/{locatorSlip}/edit', [LocatorSlipController::class, 'edit'])->name('locator-slips.edit');
    Route::put('/locator-slips/{locatorSlip}', [LocatorSlipController::class, 'update'])->name('locator-slips.update');

    Route::post('/employees/{employee}/pds-reviews', [PdsSectionReviewController::class, 'store'])->name('pds-reviews.store');

    // SALN Routes
    Route::get('salns/{saln}/download', [SalnController::class, 'download'])->name('salns.download');
    Route::resource('salns', SalnController::class);

    // Travel Order Routes
    Route::resource('travel-orders', TravelOrderController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/hr/travel-orders', [TravelOrderController::class, 'adminIndex'])->name('hr.travel-orders.index')->middleware('role:ADMIN,HRSTAFF,DIRECTOR,CHIEF,REGIONALDIRECTOR,REGIONAL DIRECTOR');
    Route::put('/travel-orders/{travelOrder}/status', [TravelOrderController::class, 'updateStatus'])->name('travel-orders.update-status')->middleware('role:CHIEF,REGIONALDIRECTOR,REGIONAL DIRECTOR,DIRECTOR');

    // Compensatory Time-Off Routes
    Route::get('/hr/cto', [CtoController::class, 'adminIndex'])->name('hr.cto.index')->middleware('role:ADMIN,HRSTAFF,CHIEF');
    Route::put('/cto/{ctoRequest}/status', [CtoController::class, 'updateStatus'])->name('cto.update-status')->middleware('role:CHIEF,HRSTAFF,ADMIN');
});

require __DIR__.'/auth.php';
