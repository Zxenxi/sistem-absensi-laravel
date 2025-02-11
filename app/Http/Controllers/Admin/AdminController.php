<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\PiketSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        // Hitung total siswa dan guru
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru  = User::where('role', 'guru')->count();

        // Hitung absensi hari ini dan jadwal piket hari ini
        $todayAttendance = Attendance::whereDate('waktu', Carbon::today())->count();
        $todayPiket      = PiketSchedule::where('schedule_date', Carbon::today()->toDateString())->count();

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
            'chartLabels',
            'chartData'
        ));
    }

    // Endpoint untuk DataTable absensi siswa
    public function getStudentAttendances(Request $request)
    {
        $attendances = Attendance::with(['siswa.kelas'])
                        ->whereNotNull('siswa_id')
                        ->orderBy('waktu', 'desc');

        return DataTables::of($attendances)
            ->addIndexColumn() // Menambahkan kolom nomor (index)
            ->addColumn('nama_siswa', function ($attendance) {
                return $attendance->siswa->name ?? 'N/A';
            })
            ->addColumn('kelas', function ($attendance) {
                return $attendance->siswa->kelas->kelas ?? 'N/A';
            })
            ->addColumn('jurusan', function ($attendance) {
                return $attendance->siswa->kelas->jurusan ?? 'N/A';
            })
            ->editColumn('waktu', function ($attendance) {
                return Carbon::parse($attendance->waktu)->format('d/m/Y H:i');
            })
            ->editColumn('status', function ($attendance) {
                $status = $attendance->status;
                $class = '';
                if ($status == 'Hadir') {
                    $class = 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100';
                } elseif($status == 'Sakit') {
                    $class = 'text-orange-700 bg-orange-100 dark:bg-orange-700 dark:text-orange-100';
                } elseif($status == 'Izin') {
                    $class = 'text-blue-700 bg-blue-100 dark:bg-blue-700 dark:text-blue-100';
                } else {
                    $class = 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100';
                }
                return '<span class="px-2 py-1 font-semibold leading-tight rounded-full '.$class.'">' . $status . '</span>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    // Endpoint untuk DataTable absensi guru
    public function getTeacherAttendances(Request $request)
    {
        $attendances = Attendance::with('guru')
                        ->whereNotNull('guru_id')
                        ->orderBy('waktu', 'desc');

        return DataTables::of($attendances)
            ->addIndexColumn() // Menambahkan kolom nomor
            ->addColumn('nama_guru', function ($attendance) {
                return $attendance->guru->name ?? 'N/A';
            })
            ->editColumn('waktu', function ($attendance) {
                return Carbon::parse($attendance->waktu)->format('d/m/Y H:i');
            })
            ->editColumn('status', function ($attendance) {
                $status = $attendance->status;
                $class = '';
                if ($status == 'Hadir') {
                    $class = 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100';
                } elseif($status == 'Sakit') {
                    $class = 'text-orange-700 bg-orange-100 dark:bg-orange-700 dark:text-orange-100';
                } elseif($status == 'Izin') {
                    $class = 'text-blue-700 bg-blue-100 dark:bg-blue-700 dark:text-blue-100';
                } else {
                    $class = 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100';
                }
                return '<span class="px-2 py-1 font-semibold leading-tight rounded-full '.$class.'">' . $status . '</span>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }
}