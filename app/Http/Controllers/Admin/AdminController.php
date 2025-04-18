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
use Illuminate\Support\Facades\Auth; // Import Auth facade

class AdminController extends Controller
{
    /**
     * Display the dashboard page with initial data.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();

        // Data for filters
        $jurusans = Kelas::select('jurusan')->distinct()->orderBy('jurusan')->get();
        $tahunAjarans = Kelas::select('tahun_ajaran')->distinct()->orderBy('tahun_ajaran', 'desc')->get();
        $kelases = Kelas::select('kelas')->distinct()->orderBy('kelas')->get();
        $teachers = User::where('role', 'guru')->orderBy('name')->get(['id', 'name']); // Only select necessary fields

        // Core Counts
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru = User::where('role', 'guru')->count();

        // --- Refined Attendance Calculations ---
        // Count only 'hadir' and 'terlambat' for "Kehadiran Hari Ini" metric
        $presentOrLateTodayQuery = Attendance::whereDate('waktu', $today)
                                         ->whereIn('status', ['hadir', 'terlambat']);

        $todayAttendanceCount = $presentOrLateTodayQuery->count(); // Count for the display stat

        // Calculate percentage based on present/late vs total students
        $attendancePercentage = $totalSiswa > 0 ? ($todayAttendanceCount / $totalSiswa) * 100 : 0;

        // Count specifically late students
        $lateStudents = Attendance::whereDate('waktu', $today)
                                  ->where('status', 'terlambat')->count();
        // --- End Refined Calculations ---

        // Piket schedule count (Keep if needed elsewhere, otherwise optional)
        $todayPiket = PiketSchedule::whereDate('schedule_date', $today)->count();

        // Data for Pie Chart (Distribution of all statuses)
        $chartLabels = ['Hadir', 'Terlambat', 'Izin', 'Alpha'];
        $chartData = [
            Attendance::whereDate('waktu', $today)->where('status', 'hadir')->count(),
            $lateStudents, // Use the already calculated value
            Attendance::whereDate('waktu', $today)->where('status', 'izin')->count(),
            Attendance::whereDate('waktu', $today)->where('status', 'alpha')->count(),
        ];

        // Data for Line Chart (Weekly Trend - Total Attendances per day)
        $trendLabels = [];
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $trendLabels[] = $date->format('d M');
            // Count all attendance records for the trend line
            $trendData[] = Attendance::whereDate('waktu', $date)->count();
        }

        // Get authenticated user information
        $user = Auth::user();

        return view('dashboard.content.index', compact(
            'jurusans',
            'tahunAjarans',
            'kelases',
            'teachers',
            'totalSiswa',           // Available if needed, but not directly used in main UI cards
            'totalGuru',            // Available if needed, but not directly used in main UI cards
            'todayAttendanceCount', // Renamed for clarity
            'todayPiket',           // Available if needed
            'attendancePercentage', // Refined calculation
            'chartLabels',
            'chartData',
            'trendLabels',
            'trendData',
            'lateStudents',
            'user'                  // Pass the user object
        ));
    }

    /**
     * AJAX endpoint for student attendance data.
     */
    public function getStudentAttendances(Request $request)
    {
        // Eager load necessary relationships efficiently
        $query = Attendance::with(['siswa' => function ($query) {
            $query->select('id', 'name', 'kelas_id'); // Select only needed fields
        }, 'siswa.kelas' => function ($query) {
            $query->select('id', 'kelas', 'jurusan', 'tahun_ajaran'); // Select only needed fields
        }])
        ->whereNotNull('siswa_id')
        ->select('attendances.*'); // Select all from attendances

        // Apply filters robustly
        if ($request->filled('jurusan')) {
            $query->whereHas('siswa.kelas', fn ($q) => $q->where('jurusan', $request->input('jurusan')));
        }
        if ($request->filled('tahunAjaran')) {
            $query->whereHas('siswa.kelas', fn ($q) => $q->where('tahun_ajaran', $request->input('tahunAjaran')));
        }
        if ($request->filled('kelas')) {
            $query->whereHas('siswa.kelas', fn ($q) => $q->where('kelas', $request->input('kelas')));
        }

        // Order by time descending by default
        $query->orderBy('waktu', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('nama_siswa', fn ($attendance) => $attendance->siswa->name ?? '-')
            ->editColumn('kelas', fn ($attendance) => $attendance->siswa->kelas->kelas ?? '-')
            ->editColumn('jurusan', fn ($attendance) => $attendance->siswa->kelas->jurusan ?? '-')
            ->editColumn('waktu', fn ($attendance) => Carbon::parse($attendance->waktu)->isoFormat('D MMM YYYY, HH:mm')) // User-friendly format
            ->editColumn('status', function ($attendance) {
                // Add styling based on status
                $status = $attendance->status;
                $badgeClass = match(strtolower($status)) {
                    'hadir' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                    'terlambat' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200',
                    'izin' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                    'alpha' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                };
                return '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ' . $badgeClass . '">' . ucfirst($status) . '</span>';
            })
            // Raw columns are needed to render HTML (like the status badge)
             ->rawColumns(['status'])
            // Custom filtering for related columns
            ->filterColumn('nama_siswa', function ($query, $keyword) {
                $query->whereHas('siswa', fn ($q) => $q->where('name', 'like', "%{$keyword}%"));
            })
            ->filterColumn('kelas', function ($query, $keyword) {
                $query->whereHas('siswa.kelas', fn ($q) => $q->where('kelas', 'like', "%{$keyword}%"));
            })
            ->filterColumn('jurusan', function ($query, $keyword) {
                $query->whereHas('siswa.kelas', fn ($q) => $q->where('jurusan', 'like', "%{$keyword}%"));
            })
            ->make(true);
    }

    /**
     * AJAX endpoint for teacher attendance data.
     */
    public function getTeacherAttendances(Request $request)
    {
        $query = Attendance::with(['guru' => fn($q)=>$q->select('id', 'name')]) // Eager load guru name
                           ->whereNotNull('guru_id')
                           ->select('attendances.*'); // Select all from attendances

        if ($request->filled('namaGuru')) {
            // Use whereHas for consistency and potential complex conditions later
            $query->whereHas('guru', fn ($q) => $q->where('name', $request->input('namaGuru')));
        }

        // Order by time descending by default
        $query->orderBy('waktu', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('nama_guru', fn ($attendance) => $attendance->guru->name ?? '-')
            ->editColumn('waktu', fn ($attendance) => Carbon::parse($attendance->waktu)->isoFormat('D MMM YYYY, HH:mm'))
            ->editColumn('status', function ($attendance) {
                // Add styling based on status (consistent with student status)
                $status = $attendance->status;
                 $badgeClass = match(strtolower($status)) {
                    'hadir' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                    'terlambat' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200',
                    'izin' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                    'alpha' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                };
                return '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ' . $badgeClass . '">' . ucfirst($status) . '</span>';
            })
            ->rawColumns(['status']) // Allow HTML rendering for status
            ->filterColumn('nama_guru', function ($query, $keyword) {
                 $query->whereHas('guru', fn ($q) => $q->where('name', 'like', "%{$keyword}%"));
            })
            ->make(true);
    }
}