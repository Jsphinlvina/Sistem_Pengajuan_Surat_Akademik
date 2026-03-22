<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StaffDashboardController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('login-admin', function (){
    return view('auth.login-admin');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'] )->name('dashboard');

// Admin
Route::middleware(['auth', 'role:0'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'] )->name('admin.dashboard');
    Route::resource('user', UserController::class);
    Route::patch('/user/{user}/status',[UserController::class, 'updateStatus'])->name('user.status');
    Route::resource('program-studi', ProgramStudiController::class);
    Route::patch('/program-studi/{programStudi}/status',[ProgramStudiController::class, 'updateStatus'])->name('program-studi.status');
});

// Staff
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'] )->name('staff.dashboard');
    Route::resource('mata-kuliah', MatakuliahController::class);
    Route::resource('periode-semester', PeriodeSemesterController::class);
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('kurikulum', KurikulumController::class);
    Route::post('/kurikulum/{kurikulum}/mata-kuliah/import', [KurikulumController::class, 'importMataKuliah'])
        ->name('kurikulum.mata-kuliah.import');
    Route::post('/kurikulum/{kurikulum}/mata-kuliah/store-import', [KurikulumController::class, 'storeImportMataKuliah'])
        ->name('kurikulum.mata-kuliah.store-import');
});


require __DIR__.'/auth.php';
