<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PharmaceuticalItemController;
use App\Http\Controllers\SupplyItemController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\PatientMedicationController;
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

    Route::resource('suppliers', SupplierController::class);
    Route::resource('pharmaceutical_items', PharmaceuticalItemController::class);
    Route::resource('supply_items', SupplyItemController::class);
    Route::resource('requisitions', RequisitionController::class);
    Route::resource('patient_medications', PatientMedicationController::class);
});

require __DIR__.'/auth.php';
