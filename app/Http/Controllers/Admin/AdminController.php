<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\PiketSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Hitung total siswa dan guru dari tabel users berdasarkan role
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru  = User::where('role', 'guru')->count();

        // Hitung absensi hari ini dan jadwal piket hari ini
        $todayAttendance = Attendance::whereDate('waktu', Carbon::today())->count();
        $todayPiket      = PiketSchedule::where('schedule_date', Carbon::today()->toDateString())->count();

        // Ambil 10 absensi terbaru untuk siswa dan guru secara terpisah
        $latestStudentAttendances = Attendance::with('siswa')
            ->whereNotNull('siswa_id')
            ->orderBy('waktu', 'desc')
            ->limit(10)
            ->get();

        $latestTeacherAttendances = Attendance::with('guru')
            ->whereNotNull('guru_id')
            ->orderBy('waktu', 'desc')
            ->limit(10)
            ->get();

        // Data untuk chart: distribusi status absensi hari ini
        $attendanceDistribution = Attendance::select('status', DB::raw('count(*) as count'))
            ->whereDate('waktu', Carbon::today())
            ->groupBy('status')
            ->pluck('count', 'status');

        $chartLabels = $attendanceDistribution->keys();
        $chartData   = $attendanceDistribution->values();

        return view('dashboard.content.index', compact(
            'totalSiswa',
            'totalGuru',
            'todayAttendance',
            'todayPiket',
            'latestStudentAttendances',
            'latestTeacherAttendances',
            'chartLabels',
            'chartData'
        ));
    }
}