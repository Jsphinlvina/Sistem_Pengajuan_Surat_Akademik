<?php

use App\Http\Controllers\DosenController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\PeriodeSemesterController;
use App\Http\Controllers\MahasiswaPeriodeSemesterController;
use App\Http\Controllers\TemplateSuratController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('admin', function (){
    return view('auth.login-admin');
});

Route::get('staff', function (){
    return view('auth.login-staff');
});

// Mahasiswa
Route::middleware(['web', 'auth:mahasiswa'])->group(function () {
   Route::get('/mahasiswa/dashboard', [MahasiswaDashboardController::class, 'index'] )->name('mahasiswa.dashboard');
   Route::resource('pengajuan', PengajuanController::class);
   Route::post('/pengajuan/template', [PengajuanController::class, 'redirectHalamanPengajuan'])->name('pengajuan.redirect');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin
Route::middleware(['auth', 'role:0'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'] )->name('admin.dashboard');

    //User
    Route::resource('user', UserController::class);
    Route::patch('/user/{user}/status',[UserController::class, 'updateStatus'])->name('user.status');

    // Program Studi
    Route::resource('program-studi', ProgramStudiController::class);
    Route::patch('/program-studi/{program_studi}/status',[ProgramStudiController::class, 'updateStatus'])->name('program-studi.status');
});

// Staff
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'] )->name('staff.dashboard');

    // Mata Kuliah
    Route::resource('mata-kuliah', MatakuliahController::class);
    Route::get('/mata-kuliah/template/download', function (){
       $path = storage_path('app/templates/template_import_mata_kuliah.xlsx');
       return response()->download($path, 'template_import_mata_kuliah.xlsx');
    })->name('mata-kuliah.template.download');

    // Periode Semester
    Route::resource('periode-semester', PeriodeSemesterController::class);
    Route::post('/periode-semester/{periode_semester}/mahasiswa-periode-semester/import', [PeriodeSemesterController::class, 'importMahasiswaPeriodeSemester'])
        ->name('periode-semester.mahasiswa-periode-semester.import');
    Route::post('/periode-semester/{periode_semester}/mahasiswa-periode-semester/store-import', [PeriodeSemesterController::class, 'storeImportMahasiswaPeriodeSemester'])
        ->name('periode-semester.mahasiswa-periode-semester.store-import');
    Route::patch('/periode-semester/{periode_semester}/status', [PeriodeSemesterController::class, 'updateStatus'])->name('periode-semester.status');

    // Mahasiswa Periode
    Route::resource('mahasiswa-periode-semester', MahasiswaPeriodeSemesterController::class);
    Route::get('/mahasiswa-periode-semester/template/download', function (){
       $path = storage_path('app/templates/template_import_mahasiswa_periode.xlsx');
       return response()->download($path, 'template_import_mahasiswa_periode.xlsx');
    })->name('mahasiswa-periode-semester.template.download');

    // Mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::post('/mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import');
    Route::get('/mahasiswa/template/download', function (){
        $path = storage_path('app/templates/template_import_mahasiswa.xlsx');
        return response()->download($path, 'template_import_mahasiswa.xlsx');
    })->name('mahasiswa.template.download');

    // Dosen
    Route::resource('dosen', DosenController::class);
    Route::post('/dosen/import', [DosenController::class, 'import'])
    ->name('dosen.import');
    Route::get('/dosen/template/download', function (){
       $path = storage_path('app/templates/template_import_dosen.xlsx');
       return response()->download($path, 'template_import_dosen.xlsx');
    })->name('dosen.template.download');

    // Kurikulum
    Route::resource('kurikulum', KurikulumController::class);
    Route::post('/kurikulum/{kurikulum}/mata-kuliah/import', [KurikulumController::class, 'importMataKuliah'])
        ->name('kurikulum.mata-kuliah.import');
    Route::post('/kurikulum/{kurikulum}/mata-kuliah/store-import', [KurikulumController::class, 'storeImportMataKuliah'])
        ->name('kurikulum.mata-kuliah.store-import');
    Route::patch('/kurikulum/{kurikulum}/status',[KurikulumController::class, 'updateStatus'])->name('kurikulum.status');

    // Template Surat
    Route::resource('template-surat', TemplateSuratController::class);
    Route::get('/template-surat/{templateSurat}/preview', [TemplateSuratController::class, 'preview'])->name('template-surat.preview');
    Route::patch('/template-surat/{templateSurat}/status',[TemplateSuratController::class, 'updateStatus'])->name('template-surat.status');
    Route::get('/template-surat/{templateSurat}/download', [TemplateSuratController::class, 'download'])->name('template-surat.download');
});




require __DIR__.'/auth.php';
