<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('employees', EmployeeController::class)->middleware('role:ADMIN,HRSTAFF,DIRECTOR');
    Route::get('/pds', [App\Http\Controllers\PdsController::class, 'index'])->name('pds.index');
    Route::get('/pds/edit', [App\Http\Controllers\PdsController::class, 'edit'])->name('pds.edit');
    Route::put('/pds', [App\Http\Controllers\PdsController::class, 'update'])->name('pds.update');
});

require __DIR__.'/auth.php';
