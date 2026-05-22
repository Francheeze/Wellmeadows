<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WardController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\StaffRotaController;


Route::resource('beds', BedController::class);

Route::resource('staff-rota', StaffRotaController::class);

Route::resource('wards', WardController::class);

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';