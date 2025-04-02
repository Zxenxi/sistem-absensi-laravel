<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Attendance;
use App\Models\PiketSchedule;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    /**
     * Tampilkan halaman dashboard dengan data awal.
     */
    public function index(Request $request)
    {
        // Ambil data unik untuk filter siswa
        $jurusans = Kelas::select('jurusan')->distinct()->get();
        $tahunAjarans = Kelas::select('tahun_ajaran')->distinct()->get();
        $kelases = Kelas::select('kelas')->distinct()->get();

        // Ambil data guru untuk filter dropdown guru
        $teachers = User::where('role', 'guru')->orderBy('name')->get();

        // Hitung total siswa dan guru.
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru = User::where('role', 'guru')->count();

        // Ambil data presensi hari ini (misal, berdasarkan kolom 'waktu').
        $today = Carbon::today();
        $todayAttendance = Attendance::whereDate('waktu', $today)->count();

        // Ambil data jadwal piket hari ini.
        $todayPiket = PiketSchedule::whereDate('schedule_date', $today)->count();

        // Hitung persentase kehadiran.
        $attendancePercentage = $totalSiswa > 0 ? ($todayAttendance / $totalSiswa) * 100 : 0;

        // Hitung jumlah siswa terlambat.
        $lateStudents = Attendance::whereDate('waktu', $today)
            ->where('status', 'terlambat')->count();

        // Data untuk chart (contoh sederhana)
        $chartLabels = ['Hadir', 'Terlambat', 'Izin', 'Alpha'];
        $chartData = [
            Attendance::whereDate('waktu', $today)->where('status', 'hadir')->count(),
            Attendance::whereDate('waktu', $today)->where('status', 'terlambat')->count(),
            Attendance::whereDate('waktu', $today)->where('status', 'izin')->count(),
            Attendance::whereDate('waktu', $today)->where('status', 'alpha')->count(),
        ];

        // Data trend kehadiran 7 hari terakhir.
        $trendLabels = [];
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $trendLabels[] = $date->format('d M');
            $trendData[] = Attendance::whereDate('waktu', $date)->count();
        }

        return view('dashboard.content.index', compact(
            'jurusans',
            'tahunAjarans',
            'kelases',
            'teachers',
            'totalSiswa',
            'totalGuru',
            'todayAttendance',
            'todayPiket',
            'attendancePercentage',
            'chartLabels',
            'chartData',
            'trendLabels',
            'trendData',
            'lateStudents'
        ));
    }

    /**
     * Endpoint AJAX untuk mengambil data presensi siswa.
     */
    public function getStudentAttendances(Request $request)
    {
        // Query untuk mengambil absensi siswa beserta relasi ke kelas.
        $query = Attendance::with(['siswa.kelas'])
            ->whereNotNull('siswa_id');

        // Terapkan filter berdasarkan jurusan.
        if ($request->filled('jurusan')) {
            $jurusan = $request->input('jurusan');
            $query->whereHas('siswa.kelas', function ($q) use ($jurusan) {
                $q->where('jurusan', $jurusan);
            });
        }

        // Terapkan filter berdasarkan tahun ajaran.
        if ($request->filled('tahunAjaran')) {
            $tahunAjaran = $request->input('tahunAjaran');
            $query->whereHas('siswa.kelas', function ($q) use ($tahunAjaran) {
                $q->where('tahun_ajaran', $tahunAjaran);
            });
        }

        // Terapkan filter berdasarkan kelas.
        if ($request->filled('kelas')) {
            $kelas = $request->input('kelas');
            $query->whereHas('siswa.kelas', function ($q) use ($kelas) {
                $q->where('kelas', $kelas);
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('nama_siswa', function ($attendance) {
                return $attendance->siswa ? $attendance->siswa->name : '-';
            })
            ->editColumn('kelas', function ($attendance) {
                return ($attendance->siswa && $attendance->siswa->kelas) ? $attendance->siswa->kelas->kelas : '-';
            })
            ->editColumn('jurusan', function ($attendance) {
                return ($attendance->siswa && $attendance->siswa->kelas) ? $attendance->siswa->kelas->jurusan : '-';
            })
            ->editColumn('waktu', function ($attendance) {
                return \Carbon\Carbon::parse($attendance->waktu)->format('d M Y H:i:s');
            })
            // Filter global khusus untuk kolom nama_siswa, kelas, dan jurusan.
            ->filterColumn('nama_siswa', function ($query, $keyword) {
                $query->whereHas('siswa', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('kelas', function ($query, $keyword) {
                $query->whereHas('siswa.kelas', function ($q) use ($keyword) {
                    $q->where('kelas', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('jurusan', function ($query, $keyword) {
                $query->whereHas('siswa.kelas', function ($q) use ($keyword) {
                    $q->where('jurusan', 'like', "%{$keyword}%");
                });
            })
            ->make(true);
    }

    /**
     * Endpoint AJAX untuk mengambil data presensi guru.
     */
    public function getTeacherAttendances(Request $request)
    {
        // Query untuk mengambil absensi guru.
        $query = Attendance::with('guru')
            ->whereNotNull('guru_id');

        // Terapkan filter berdasarkan nama guru dari dropdown.
        if ($request->filled('namaGuru')) {
            $namaGuru = $request->input('namaGuru');
            $query->whereHas('guru', function ($q) use ($namaGuru) {
                $q->where('name', $namaGuru);
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('nama_guru', function ($attendance) {
                return $attendance->guru ? $attendance->guru->name : '-';
            })
            ->editColumn('waktu', function ($attendance) {
                return \Carbon\Carbon::parse($attendance->waktu)->format('d M Y H:i:s');
            })
            // Filter global khusus untuk kolom nama_guru.
            ->filterColumn('nama_guru', function ($query, $keyword) {
                $query->whereHas('guru', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->make(true);
    }
}