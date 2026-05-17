<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PharmaceuticalItemController;
use App\Http\Controllers\SupplyItemController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\PatientMedicationController;
use App\Http\Controllers\InPatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\OutPatientController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\NextOfKinController;
use App\Http\Controllers\LocalDoctorController;
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
    
    Route::prefix('patient_medications')->name('patient_medications.')->group(function () {
        Route::get('/',        [PatientMedicationController::class, 'index'])->name('index');
        Route::get('/create',  [PatientMedicationController::class, 'create'])->name('create');
        Route::post('/',       [PatientMedicationController::class, 'store'])->name('store');

    // These three use ?patient_number=&drug_number=&start_date= query params
    // instead of a route segment, because the PK is composite.
        Route::get('/show',    [PatientMedicationController::class, 'show'])->name('show');
        Route::get('/edit',    [PatientMedicationController::class, 'edit'])->name('edit');
        Route::put('/update',  [PatientMedicationController::class, 'update'])->name('update');
        Route::delete('/destroy', [PatientMedicationController::class, 'destroy'])->name('destroy');
    });

    // Patient Management Module
    Route::resource('patients',       PatientController::class);
    Route::resource('local_doctors',  LocalDoctorController::class);
    Route::resource('appointments',   AppointmentController::class);
    Route::resource('exam_results',   ExamResultController::class);
    Route::resource('in_patients',    InPatientController::class);
    Route::resource('out_patients',   OutPatientController::class);

    // Nested resource
    Route::resource('patients.next_of_kins', NextOfKinController::class);

    // Custom actions
    Route::post('appointments/{appointment}/result',      [AppointmentController::class, 'recordResult'])->name('appointments.record_result');
    Route::patch('in_patients/{in_patient}/discharge',    [InPatientController::class,   'discharge'])->name('in_patients.discharge');
});

require __DIR__.'/auth.php';
