<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NursingStationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\LabOrderController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\IpdController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\AccountingDashboardController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management Routes
    Route::middleware(['permission:users.view'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
    
    // Patient Management Routes
    Route::middleware(['permission:patients.view'])->group(function () {
        Route::resource('patients', PatientController::class);
        Route::post('/patients/{patient}/toggle-status', [PatientController::class, 'toggleStatus'])->name('patients.toggle-status');
    });
    
    // Appointment Management Routes
    Route::middleware(['permission:appointments.view-all|appointments.view-own'])->group(function () {
        Route::resource('appointments', AppointmentController::class);
        Route::post('/appointments/create-walk-in', [AppointmentController::class, 'createWalkIn'])->name('appointments.create-walk-in');
        Route::post('/appointments/{appointment}/check-in', [AppointmentController::class, 'checkIn'])->name('appointments.check-in');
        Route::post('/appointments/{appointment}/start-consultation', [AppointmentController::class, 'startConsultation'])->name('appointments.start-consultation');
        Route::post('/appointments/{appointment}/complete-consultation', [AppointmentController::class, 'completeConsultation'])->name('appointments.complete-consultation');
        Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::post('/appointments/{appointment}/reopen', [AppointmentController::class, 'reopen'])->name('appointments.reopen');
        Route::get('/doctor/appointments', [AppointmentController::class, 'doctorDashboard'])->name('doctor.appointments');
    });
    
    // Nursing Station Routes
    Route::middleware(['permission:nursing.view-dashboard'])->group(function () {
        Route::get('/nursing/dashboard', [NursingStationController::class, 'dashboard'])->name('nursing.dashboard');
        Route::get('/nursing/appointments/{appointment}/vitals', [NursingStationController::class, 'showVitalsForm'])->name('nursing.vitals-form');
        Route::post('/nursing/appointments/{appointment}/vitals', [NursingStationController::class, 'recordVitals'])->name('nursing.record-vitals');
    });
    
    // Consultation Management Routes
    Route::middleware(['permission:consultations.view-all|consultations.view-own'])->group(function () {
        Route::resource('consultations', ConsultationController::class);
        Route::post('/consultations/{consultation}/complete', [ConsultationController::class, 'complete'])->name('consultations.complete');
        Route::post('/consultations/{consultation}/prescription', [ConsultationController::class, 'addPrescription'])->name('consultations.add-prescription');
        Route::post('/consultations/{consultation}/lab-order', [ConsultationController::class, 'addLabOrder'])->name('consultations.add-lab-order');
        Route::post('/consultations/{consultation}/resume', [ConsultationController::class, 'resume'])->name('consultations.resume');
        Route::post('/consultations/{consultation}/admit', [ConsultationController::class, 'admitPatient'])->name('consultations.admit');
        Route::get('/appointments/{appointment}/start-consultation-form', [ConsultationController::class, 'startFromAppointment'])->name('consultations.start-from-appointment');
    });
    
    // Laboratory & Radiology Routes
    Route::middleware(['permission:lab.view-dashboard|radiology.view-dashboard'])->group(function () {
        Route::get('/lab/dashboard', [LabOrderController::class, 'dashboard'])->name('lab.dashboard');
        Route::get('/radiology/dashboard', [LabOrderController::class, 'radiologyDashboard'])->name('radiology.dashboard');
        Route::get('/lab-orders', [LabOrderController::class, 'index'])->name('lab.index');
        Route::get('/lab-orders/{labOrder}', [LabOrderController::class, 'show'])->name('lab.show');
    
    // Sample collection
    Route::get('/lab-orders/{labOrder}/collect-sample', [LabOrderController::class, 'collectSampleForm'])->name('lab.collect-sample');
    Route::post('/lab-orders/{labOrder}/collect-sample', [LabOrderController::class, 'storeSampleCollection'])->name('lab.store-sample-collection');
    
    // Lab processing
    Route::get('/lab-orders/{labOrder}/process', [LabOrderController::class, 'processForm'])->name('lab.process');
    Route::post('/lab-orders/{labOrder}/process', [LabOrderController::class, 'storeProcess'])->name('lab.store-process');
    
    // Results entry
    Route::get('/lab-orders/{labOrder}/results', [LabOrderController::class, 'resultsForm'])->name('lab.results');
    Route::post('/lab-orders/{labOrder}/results', [LabOrderController::class, 'storeResults'])->name('lab.store-results');
    
    // Radiology processing
    Route::get('/lab-orders/{labOrder}/radiology-process', [LabOrderController::class, 'radiologyProcessForm'])->name('lab.radiology-process');
    Route::post('/lab-orders/{labOrder}/radiology-process', [LabOrderController::class, 'storeRadiologyResults'])->name('lab.store-radiology-results');
    
    // Actions
    Route::post('/lab-orders/{labOrder}/report', [LabOrderController::class, 'markReported'])->name('lab.mark-reported');
    Route::post('/lab-orders/{labOrder}/cancel', [LabOrderController::class, 'cancel'])->name('lab.cancel');
    
        // Downloads
        Route::get('/lab-orders/{labOrder}/download-result', [LabOrderController::class, 'downloadResult'])->name('lab.download-result');
        Route::get('/lab-orders/{labOrder}/download-imaging', [LabOrderController::class, 'downloadImaging'])->name('lab.download-imaging');
    });
    
    // Pharmacy Routes
    Route::middleware(['permission:pharmacy.view-dashboard'])->group(function () {
        Route::get('/pharmacy/dashboard', [PharmacyController::class, 'dashboard'])->name('pharmacy.dashboard');
        Route::get('/pharmacy/prescriptions/{prescription}', [PharmacyController::class, 'show'])->name('pharmacy.show');
        Route::post('/pharmacy/prescriptions/{prescription}/dispense', [PharmacyController::class, 'dispense'])->name('pharmacy.dispense');
        Route::post('/pharmacy/prescriptions/{prescription}/cancel', [PharmacyController::class, 'cancel'])->name('pharmacy.cancel');
        Route::post('/pharmacy/prescriptions/{prescription}/mark-emergency', [PharmacyController::class, 'markEmergency'])->name('pharmacy.mark-emergency');
    });
    
    // IPD Routes
    Route::middleware(['permission:ipd.view-dashboard'])->group(function () {
        Route::get('/ipd/dashboard', [IpdController::class, 'dashboard'])->name('ipd.dashboard');
        
        // Ward Management
        Route::resource('wards', WardController::class);
        Route::post('/wards/{ward}/toggle-status', [WardController::class, 'toggleStatus'])->name('wards.toggle-status');
        Route::post('/wards/{ward}/assign-nurse', [WardController::class, 'assignNurse'])->name('wards.assign-nurse');
        
        // Bed Management
        Route::resource('beds', BedController::class);
        Route::post('/beds/{bed}/toggle-status', [BedController::class, 'toggleStatus'])->name('beds.toggle-status');
        Route::post('/beds/{bed}/mark-cleaning', [BedController::class, 'markForCleaning'])->name('beds.mark-cleaning');
        Route::post('/beds/{bed}/mark-available', [BedController::class, 'markAvailable'])->name('beds.mark-available');
        
        // Admission Management
        Route::resource('admissions', AdmissionController::class);
        Route::get('/admissions/{admission}/discharge-form', [AdmissionController::class, 'dischargeForm'])->name('admissions.discharge-form');
        Route::post('/admissions/{admission}/discharge', [AdmissionController::class, 'discharge'])->name('admissions.discharge');
        Route::post('/admissions/{admission}/transfer', [AdmissionController::class, 'transfer'])->name('admissions.transfer');
        
        // API Routes for IPD
        Route::get('/api/wards/{ward}/available-beds', [WardController::class, 'getAvailableBeds'])->name('api.wards.available-beds');
    });
    
    // Billing Routes
    Route::middleware(['permission:billing.view-dashboard'])->group(function () {
        Route::get('/accounting/dashboard', [AccountingDashboardController::class, 'index'])->name('accounting.dashboard');
        Route::get('/accounting/patient/{patient}/charges', [AccountingDashboardController::class, 'patientCharges'])->name('accounting.patient-charges');
        
        Route::resource('services', ServiceController::class);
        Route::post('/services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        Route::resource('bills', BillController::class);
        Route::post('/bills/{bill}/add-payment', [BillController::class, 'addPayment'])->name('bills.add-payment');
        Route::get('/bills/{bill}/receipt', [BillController::class, 'receipt'])->name('bills.receipt');
        Route::post('/bills/generate-from-charges/{patient}', [BillController::class, 'generateFromCharges'])->name('bills.generate-from-charges');
    });
    
    // Settings - Role & Permission Management
    Route::prefix('settings')->name('settings.')->middleware(['permission:settings.view'])->group(function () {
        // Roles & Permissions CRUD (specific routes before wildcard)
        Route::get('/roles/create', [RolePermissionController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RolePermissionController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}', [RolePermissionController::class, 'show'])->name('roles.show');
        Route::get('/roles/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RolePermissionController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RolePermissionController::class, 'destroy'])->name('roles.destroy');
        Route::post('/roles/{role}/sync-permissions', [RolePermissionController::class, 'syncPermissions'])->name('roles.sync-permissions');
        
        Route::get('/permissions', [RolePermissionController::class, 'permissions'])->name('permissions.index');
        
        // System Settings
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/update', [SettingsController::class, 'update'])->name('update');
        Route::post('/reset/{category}', [SettingsController::class, 'reset'])->name('reset');
        Route::get('/export/all', [SettingsController::class, 'export'])->name('export');
        Route::post('/import', [SettingsController::class, 'import'])->name('import');
        Route::post('/cache/clear', [SettingsController::class, 'clearCache'])->name('cache.clear');
        
        // Hospital Structure Management (only store, update, destroy - index is handled by category route)
        Route::resource('branches', BranchController::class)->only(['store', 'update', 'destroy']);
        Route::resource('departments', DepartmentController::class)->only(['store', 'update', 'destroy']);
        
        // Wildcard category route (must be last) - this handles /settings/roles, /settings/branches, /settings/departments
        Route::get('/{category}', [SettingsController::class, 'category'])->name('category');
    });
});

