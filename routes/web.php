<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PatientMedicationController;
use App\Http\Controllers\InPatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\OutPatientController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\NextOfKinController;
use App\Http\Controllers\LocalDoctorController;
use App\Http\Controllers\PharmaceuticalItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplyItemController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ReportController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WardController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\StaffRotaController;

// Public routes
use App\Http\Controllers\SearchController;

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
require __DIR__.'/auth.php';

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {

    // ==========================
    // WARD MODULE ROUTES
    // ==========================
    Route::resource('wards', WardController::class)->except(['show']);
    Route::resource('beds', BedController::class)->except(['show']);
    Route::resource('staff-rota', StaffRotaController::class)->except(['show']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Logout route
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
    
    // ==========================
    // STAFF MANAGEMENT ROUTES
    // ==========================
    
    // IMPORTANT: These are TWO different routes:
    
    // 1. ALL STAFF - Shows the list of staff (INDEX)
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    
    // 2. ADD STAFF - Shows the form to add new staff (CREATE)
    Route::get('/add-staff', [StaffController::class, 'create'])->name('staff.create');
    
    // 3. SAVE STAFF - Saves the new staff to database (STORE)
    Route::post('/add-staff', [StaffController::class, 'store'])->name('staff.store');
    
    // 4. EDIT STAFF - Shows form to edit staff
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    
    // 5. UPDATE STAFF - Saves edited staff
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    
    // 6. DELETE STAFF - Removes staff
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');

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

    // 7. SHOW STAFF - Shows a single staff member's details
    Route::get('/staff/{staff}', [StaffController::class, 'show'])->name('staff.show');

    // 8. STAFF AUTOCOMPLETE - Provides search suggestions
    Route::get('/staff/autocomplete', [StaffController::class, 'autocomplete'])->name('staff.autocomplete');

    // Department routes
    Route::resource('department', DepartmentController::class);

    // Schedule routes
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    
    // ==========================
    // REPORT MANAGEMENT ROUTES
    // ==========================
     Route::resource('incidents', IncidentController::class);
    Route::get('/reports', [\App\Http\Controllers\IncidentController::class, 'index'])->name('reports');
    Route::get('reports/create', [\App\Http\Controllers\IncidentController::class, 'create'])->name('reports.create');
    Route::post('reports', [\App\Http\Controllers\IncidentController::class, 'store'])->name('reports.store');
    Route::delete('reports/{incident}', [\App\Http\Controllers\IncidentController::class, 'destroy'])->name('reports.destroy');
});



Route::get('/debug-staff/{staff_number}', function($staff_number) {
    $staff = App\Models\Staff::with('department')->find($staff_number);
    echo "Staff attributes:\n";
    print_r($staff->attributes);
    echo "\n\nDepartment relation:\n";
    print_r($staff->department);
    exit;
});