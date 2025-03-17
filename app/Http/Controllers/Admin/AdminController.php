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
    // Menampilkan halaman dashboard
    public function index()
    {
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru  = User::where('role', 'guru')->count();

        $todayAttendance = Attendance::whereDate('waktu', Carbon::today())->count();
        $todayPiket = PiketSchedule::where('schedule_date', Carbon::today()->toDateString())->count();

        // Hitung distribusi status absensi hari ini untuk chart
        $attendanceDistribution = Attendance::select('status', DB::raw('count(*) as count'))
            ->whereDate('waktu', Carbon::today())
            ->groupBy('status')
            ->pluck('count', 'status');

        $chartLabels = $attendanceDistribution->keys();
        $chartData   = $attendanceDistribution->values();

        // Hitung persentase kehadiran (pastikan totalSiswa tidak nol)
        $attendancePercentage = $totalSiswa > 0 ? ($todayAttendance / $totalSiswa) * 100 : 0;

        // Contoh perhitungan siswa terlambat, misalnya status 'terlambat'
        $lateStudents = Attendance::where('status', 'terlambat')
            ->whereDate('waktu', Carbon::today())
            ->count();

        return view('dashboard.content.index', compact(
            'totalSiswa',
            'totalGuru',
            'todayAttendance',
            'todayPiket',
            'chartLabels',
            'chartData',
            'attendancePercentage',
            'lateStudents'
        ));
    }
    // public function index()
    // {
    //     $totalSiswa = User::where('role', 'siswa')->count();
    //     $totalGuru  = User::where('role', 'guru')->count();

    //     $todayAttendance = Attendance::whereDate('waktu', Carbon::today())->count();
    //     $todayPiket = PiketSchedule::where('schedule_date', Carbon::today()->toDateString())->count();

    //     // Hitung distribusi status absensi hari ini untuk chart
    //     $attendanceDistribution = Attendance::select('status', DB::raw('count(*) as count'))
    //         ->whereDate('waktu', Carbon::today())
    //         ->groupBy('status')
    //         ->pluck('count', 'status');

    //     $chartLabels = $attendanceDistribution->keys();
    //     $chartData   = $attendanceDistribution->values();

    //     return view('dashboard.content.index', compact(
    //         'totalSiswa',
    //         'totalGuru',
    //         'todayAttendance',
    //         'todayPiket',
    //         'chartLabels',
    //         'chartData'
    //     ));
    // }

    // Endpoint AJAX untuk DataTables absensi siswa
    public function getStudentAttendances(Request $request)
    {
        $attendances = Attendance::with(['siswa.kelas'])
            ->whereNotNull('siswa_id')
            ->orderBy('waktu', 'desc');
    
        return DataTables::of($attendances)
            ->addIndexColumn()
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
                return \Carbon\Carbon::parse($attendance->waktu)->format('d/m/Y H:i');
            })
            ->editColumn('status', function ($attendance) {
                $status = $attendance->status;
                $class = '';
                if ($status == 'Hadir') {
                    $class = 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100';
                } elseif ($status == 'Sakit') {
                    $class = 'text-orange-700 bg-orange-100 dark:bg-orange-700 dark:text-orange-700';
                } elseif ($status == 'Izin') {
                    $class = 'text-green-700 bg-blue-100 dark:bg-blue-700 dark:text-blue-400';
                } else {
                    $class = 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100';
                }
                return '<span class="px-2 py-1 font-semibold leading-tight rounded-full '.$class.'">'.$status.'</span>';
            })
            // Filter khusus untuk kolom nama_siswa
            ->filterColumn('nama_siswa', function($query, $keyword) {
                 $query->whereHas('siswa', function($q) use ($keyword) {
                      $q->where('name', 'like', "%{$keyword}%");
                 });
            })
            // Filter khusus untuk kolom kelas
            ->filterColumn('kelas', function($query, $keyword) {
                 $query->whereHas('siswa.kelas', function($q) use ($keyword) {
                      $q->where('kelas', 'like', "%{$keyword}%");
                 });
            })
            // Filter khusus untuk kolom jurusan
            ->filterColumn('jurusan', function($query, $keyword) {
                 $query->whereHas('siswa.kelas', function($q) use ($keyword) {
                      $q->where('jurusan', 'like', "%{$keyword}%");
                 });
            })
            ->rawColumns(['status'])
            ->make(true);
    }
    public function getTeacherAttendances(Request $request)
    {
        $attendances = Attendance::with('guru')
            ->whereNotNull('guru_id')
            ->orderBy('waktu', 'desc');
    
        return DataTables::of($attendances)
            ->addIndexColumn()
            ->addColumn('nama_guru', function ($attendance) {
                return $attendance->guru->name ?? 'N/A';
            })
            ->addColumn('lokasi', function ($attendance) {
                return $attendance->lokasi;
            })
            ->editColumn('waktu', function ($attendance) {
                return \Carbon\Carbon::parse($attendance->waktu)->format('d/m/Y H:i');
            })
            ->editColumn('status', function ($attendance) {
                $status = $attendance->status;
                $class = '';
                if ($status == 'Hadir') {
                    $class = 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100';
                } elseif ($status == 'Sakit') {
                    $class = 'text-orange-700 bg-orange-100 dark:bg-orange-700 dark:text-orange-700';
                } elseif ($status == 'Izin') {
                    $class = 'text-green-700 bg-blue-100 dark:bg-blue-700 dark:text-blue-400';
                } else {
                    $class = 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100';
                }
                return '<span class="px-2 py-1 font-semibold leading-tight rounded-full '.$class.'">'.$status.'</span>';
            })
            ->filterColumn('nama_guru', function($query, $keyword) {
                 $query->whereHas('guru', function($q) use ($keyword) {
                     $q->where('name', 'like', "%{$keyword}%");
                 });
            })
            ->rawColumns(['status'])
            ->make(true);
    }
        
}