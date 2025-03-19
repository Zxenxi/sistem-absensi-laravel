<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Kelas\KelasController;
use App\Http\Controllers\Siswa\SiswaController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Piket\PiketController;
use App\Http\Controllers\Report\ReportController;
// use App\Http\Controllers\ReportController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard',function(){return view('dashboard');})->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/index', [AdminController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // AJAX endpoints untuk DataTables
    Route::get('/admin/student-attendances', [AdminController::class, 'getStudentAttendances'])->name('admin.getStudentAttendances');
    Route::get('/admin/teacher-attendances', [AdminController::class, 'getTeacherAttendances'])->name('admin.getTeacherAttendances');

});

require __DIR__.'/auth.php';

Route::get('/forms',function(){
    return view('dashboard.content.forms');
})->name('forms');

Route::middleware('auth')->group(function () {
    Route::resource('kelas', KelasController::class);  // Akan menghasilkan route: index, create, store, show, edit, update, destroy.
    Route::get('/fetch', [KelasController::class, 'fetchKelas'])->name('kelas.fetch');
    Route::post('/store', [KelasController::class, 'store'])->name('kelas.store');
    Route::get('/kelas/{id}', [KelasController::class, 'show'])->name('kelas.show'); // Menambahkan rute untuk mendapatkan data kelas
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');  // Menggunakan PUT untuk update
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
});

// routes/web.php

Route::middleware('auth')->group(function () {
    Route::get('/siswa/index', [SiswaController::class, 'siswa'])->name('siswa.siswa'); // Menampilkan halaman siswa
    Route::resource('siswa', SiswaController::class);
});

// routing guru
Route::middleware('auth')->group(function () {
    Route::get('/guru/index', [GuruController::class, 'guru']); // Menampilkan halaman guru
    Route::get('/guru', [GuruController::class, 'index'])->name('fetchguru');
    Route::post('/guru', [GuruController::class, 'store']);
    Route::get('/guru/{id}', [GuruController::class, 'show'])->name('guru.show');
    Route::put('/guru/{id}', [GuruController::class, 'update'])->name('guru.update');
    Route::delete('/guru/{id}', [GuruController::class, 'destroy']);
});

Route::middleware('auth')->group(function () {
Route::get('/absensi', [AttendanceController::class, 'index'])->name('attendances.index');
Route::post('/absensi', [AttendanceController::class, 'store'])->name('absensi.store');

});

Route::middleware(['auth'])->group(function () {
    // Resource route untuk manajemen piket dengan CRUD Ajax
    Route::resource('piket', PiketController::class);
    // Routes untuk absensi
    Route::get('/absensi', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/absensi', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/absensi', [AttendanceController::class, 'store'])->name('absensi.store');
    Route::get('/attendance/{attendance}', [AttendanceController::class, 'show']);
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'update']);
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy']);
    // Route::get('/absensi', [AttendanceController::class, 'index'])->name('absensi.form');
    // Dashboard absensi untuk guru petugas piket
    Route::get('/dashboard-absensi', [AttendanceController::class, 'dashboard'])->name('attendance.dashboard');
    Route::get('/dashboard-absen', [AttendanceController::class, 'managements'])->name('attendance.attendance');

    // Route untuk export data presensi

    Route::get('/export',[ReportController::class, 'index'])->name('index');
    Route::get('/export/siswa/excel', [ReportController::class, 'exportSiswaExcel'])->name('export.siswa.excel');
    Route::get('/export/siswa/pdf', [ReportController::class, 'exportSiswaPDF'])->name('export.siswa.pdf');
    Route::get('/export/guru/excel', [ReportController::class, 'exportGuruExcel'])->name('export.guru.excel');
    Route::get('/export/guru/pdf', [ReportController::class, 'exportGuruPDF'])->name('export.guru.pdf');
    
    Route::get('/riwayat-presensi', [AttendanceController::class, 'history'])->name('attendance.history');
});



// routing view view

// Route::get('/charts',function(){
// return view('dashboard.charts');
// })->name('charts');
// Route::get('/buttons',function(){
// return view('dashboard.buttons');
// })->name('buttons');
// Route::get('/modals',function(){
// return view('dashboard.modals');
// })->name('modals');
// Route::get('/tables',function(){
// return view('dashboard.tables');
// })->name('tables');
// Route::get('/cards',function(){
// return view('dashboard.cards');
// })->name('cards');