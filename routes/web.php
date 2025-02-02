<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Kelas\kelascontroller;
use App\Http\Controllers\Siswa\SiswaController;
use App\Http\Controllers\Attendance\AttendanceController;

// Route::get('/', function () {
//     return view('welcome');
// });

// use App\Http\Controllers\AttendanceController;
// use App\Http\Controllers\AdminController;

// Route::middleware(['auth'])->group(function () {
//     // Dashboard Admin
//     Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
//     // Absensi Guru (hanya absen diri)
//     Route::get('/attendance', [AttendanceController::class, 'teacherIndex'])->name('attendance.index');
//     Route::post('/attendance/mark', [AttendanceController::class, 'teacherMark'])->name('attendance.mark');

//     // Rekaman Absensi Siswa (atau absensi siswa mandiri jika diizinkan)
//     Route::get('/my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.myAttendance');
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard.content.index');
    })->name('index');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard'); //for admin management user data
});

require __DIR__.'/auth.php';

Route::get('/index',function(){
    return view('dashboard.content.index');
})->name('index');
Route::get('/forms',function(){
    return view('dashboard.content.forms');
})->name('forms');

Route::middleware('auth')->group(function () {
    Route::resource('kelas', kelasController::class);  // Akan menghasilkan route: index, create, store, show, edit, update, destroy.
    Route::get('/fetch', [kelasController::class, 'fetchKelas'])->name('kelas.fetch');
    Route::post('/store', [kelasController::class, 'store'])->name('kelas.store');
    Route::get('/kelas/{id}', [kelasController::class, 'show'])->name('kelas.show'); // Menambahkan rute untuk mendapatkan data kelas
    Route::put('/kelas/{id}', [kelasController::class, 'update'])->name('kelas.update');  // Menggunakan PUT untuk update
    Route::delete('/kelas/{id}', [kelasController::class, 'destroy'])->name('kelas.destroy');
});

// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/siswa/index', [SiswaController::class, 'siswa'])->name('siswa.siswa'); // Menampilkan halaman siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index'); // Menampilkan halaman siswa
    Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create'); // Menampilkan form tambah siswa
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store'); // Menyimpan siswa baru
    Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit'); // Menampilkan form edit siswa
    Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update'); // Update siswa
    Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy'); // Hapus siswa
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
Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
Route::get('/absensi', function () {
    return view('absensi');
})->name('absensi.form');


Route::post('/absensi', [AttendanceController::class, 'store'])->name('absensi.store');
});




// routing view view

Route::get('/charts',function(){
return view('dashboard.charts');
})->name('charts');
Route::get('/buttons',function(){
return view('dashboard.buttons');
})->name('buttons');
Route::get('/modals',function(){
return view('dashboard.modals');
})->name('modals');
Route::get('/tables',function(){
return view('dashboard.tables');
})->name('tables');
Route::get('/cards',function(){
return view('dashboard.cards');
})->name('cards');