<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public routes
use App\Http\Controllers\SearchController;

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
require __DIR__.'/auth.php';

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    
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

    // 7. SHOW STAFF - Shows a single staff member's details
    Route::get('/staff/{staff}', [StaffController::class, 'show'])->name('staff.show');

        // Department routes
    Route::get('/department', [DepartmentController::class, 'index'])->name('department.index');
    Route::get('/department/{name}', [DepartmentController::class, 'show'])->name('department.show');

    Route::get('/schedules', function () {
        return view('schedules');
    })->name('schedules');
    
    // ==========================
    // REPORT MANAGEMENT ROUTES
    // ==========================
    Route::get('/reports', [\App\Http\Controllers\IncidentController::class, 'index'])->name('reports');
    Route::get('reports/create', [\App\Http\Controllers\IncidentController::class, 'create'])->name('reports.create');
    Route::post('reports', [\App\Http\Controllers\IncidentController::class, 'store'])->name('reports.store');
    Route::delete('reports/{incident}', [\App\Http\Controllers\IncidentController::class, 'destroy'])->name('reports.destroy');
});