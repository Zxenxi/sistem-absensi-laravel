<?php

namespace App\Http\Controllers\Attendance;

use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendances = Attendance::with(['siswa', 'guru'])->orderBy('waktu', 'desc')->get();
        if ($request->ajax()) {
            return response()->json(['attendances' => $attendances]);
        }
        return view('attendances.index');
    }
    
    public function managements() {
        $attendances = Attendance::with(['siswa', 'guru'])->orderBy('waktu', 'desc')->get();
        return view('attendances.attendance', compact('attendances'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'role'       => 'required|in:siswa,guru',
            'lokasi'     => 'required|string',
            'foto_wajah' => 'required|string',
        ]);

        // Koordinat sekolah & radius absensi
        $schoolLat = -7.709829747808012;
        $schoolLng = 110.0077439397974;
        $allowedRadius = 60000;

        // Parsing lokasi
        $lokasiUser = explode(',', $request->lokasi);
        if (count($lokasiUser) !== 2) {
            return response()->json([
                'message' => 'Format lokasi tidak valid. Pastikan formatnya: "latitude, longitude".'
            ], 400);
        }
        $userLat = floatval(trim($lokasiUser[0]));
        $userLng = floatval(trim($lokasiUser[1]));

        // Hitung jarak (pastikan helper calculateDistance() sudah tersedia)
        $distance = calculateDistance($userLat, $userLng, $schoolLat, $schoolLng);
        if ($distance > $allowedRadius) {
            return response()->json([
                'message' => 'Absensi gagal: Anda berada di luar jangkauan absensi.'
            ], 403);
        }

        $now   = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        // Cek apakah hari ini merupakan hari libur (weekend atau libur nasional)
        $year = $now->year;
        $cacheKey = "public_holidays_{$year}_ID";
        $holidayDates = Cache::remember($cacheKey, now()->addDay(), function () use ($year) {
            $response = Http::get("https://date.nager.at/api/v3/PublicHolidays/{$year}/ID");
            if ($response->successful()) {
                // Mengambil semua tanggal libur dari API
                return collect($response->json())->pluck('date')->toArray();
            }
            return [];
        });

        if ($now->isWeekend() || in_array($today, $holidayDates)) {
            return response()->json([
                'message' => 'Absensi tidak bisa dilakukan karena libur'
            ], 403);
        }

        // Jika absen setelah jam 07:00 WIB, otomatis statusnya menjadi 'Terlambat'
        $attendanceDeadline = Carbon::createFromTime(7, 0, 0, 'Asia/Jakarta');
        $status = $now->gt($attendanceDeadline) ? 'Terlambat' : 'Hadir';

        if ($request->role === 'siswa') {
            $user = Auth::user();
            if (!$user->nisn) {
                return response()->json([
                    'message' => 'Siswa tidak ditemukan. Pastikan akun Anda sudah terhubung dengan data siswa yang valid.'
                ], 404);
            }
            $absenHariIni = Attendance::where('siswa_id', $user->id)
                ->whereDate('waktu', $today)
                ->first();
            if ($absenHariIni) {
                return response()->json([
                    'message' => 'Anda sudah melakukan absensi hari ini.'
                ], 403);
            }
            Attendance::create([
                'siswa_id'  => $user->id,
                'guru_id'   => null,
                'waktu'     => $now->toDateTimeString(),
                'status'    => $status,
                'lokasi'    => $request->lokasi,
                'foto_wajah'=> $this->saveFoto($request->foto_wajah),
            ]);
        } elseif ($request->role === 'guru') {
            if (Auth::user()->role !== 'guru') {
                return response()->json([
                    'message' => 'Guru tidak ditemukan. Pastikan akun Anda memiliki role guru yang valid.'
                ], 404);
            }
            $guru = Auth::user();
            $absenHariIni = Attendance::where('guru_id', $guru->id)
                ->whereDate('waktu', $today)
                ->first();
            if ($absenHariIni) {
                return response()->json([
                    'message' => 'Anda sudah melakukan absensi hari ini.'
                ], 403);
            }
            Attendance::create([
                'guru_id'   => $guru->id,
                'siswa_id'  => null,
                'waktu'     => $now->toDateTimeString(),
                'status'    => $status,
                'lokasi'    => $request->lokasi,
                'foto_wajah'=> $this->saveFoto($request->foto_wajah),
            ]);
        }

        return response()->json(['message' => 'Absensi berhasil disimpan.']);
    }

    public function show(Attendance $attendance)
    {
        return response()->json(['attendance' => $attendance]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Sakit,Izin,Alfa,Terlambat'
        ]);

        $attendance->update($request->only('status'));
        return response()->json(['message' => 'Absensi berhasil diperbarui.', 'attendance' => $attendance]);
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return response()->json(['message' => 'Absensi berhasil dihapus.']);
    }

    /**
     * View dashboard untuk guru yang dijadwalkan sebagai petugas piket.
     * Jika guru memiliki jadwal piket untuk hari ini, tampilkan dashboard absensi;
     * jika tidak, tampilkan form absensi biasa.
     */
    public function dashboard()
    {
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        
        // Ambil jadwal piket untuk guru yang sedang login pada hari ini
        $piket = \App\Models\PiketSchedule::where('guru_id', Auth::user()->id)
                    ->whereDate('schedule_date', $today)
                    ->first();
        
        if ($piket) {
            if ($piket->start_time && $piket->end_time) {
                $start = Carbon::parse($piket->start_time, 'Asia/Jakarta');
                $end   = Carbon::parse($piket->end_time, 'Asia/Jakarta');
                if ($now->between($start, $end)) {
                    $attendances = Attendance::with(['siswa', 'guru'])
                        ->orderBy('waktu', 'desc')
                        ->get();
                    return view('attendances.dashboard', compact('attendances'));
                } else {
                    return view('attendances.index');
                }
            } else {
                $attendances = Attendance::with(['siswa', 'guru'])
                    ->orderBy('waktu', 'desc')
                    ->get();
                return view('attendances.dashboard', compact('attendances'));
            }
        }
        
        return view('attendances.index');
    }

    protected function saveFoto($fotoBase64)
    {
        $fotoDir = public_path('storage/absensi_foto');
        if (!file_exists($fotoDir)) {
            mkdir($fotoDir, 0777, true);
        }
        if ($fotoBase64) {
            $fotoData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $fotoBase64));
            $fotoName = uniqid() . '.png';
            $fotoPath = 'storage/absensi_foto/' . $fotoName;
            file_put_contents(public_path($fotoPath), $fotoData);
            return $fotoPath;
        }
        return null;
    }
    public function history(Request $request)
    {
        $user = Auth::user();
    
        // Bangun query berdasarkan peran user
        if ($user->role === 'siswa') {
            $attendances = Attendance::where('siswa_id', $user->id)
                ->with(['siswa', 'guru'])
                ->orderBy('waktu', 'desc');
        } elseif ($user->role === 'guru') {
            $attendances = Attendance::where('guru_id', $user->id)
                ->with(['siswa', 'guru'])
                ->orderBy('waktu', 'desc');
        } else {
            // Misal untuk admin atau lainnya
            $attendances = Attendance::with(['siswa', 'guru'])
                ->orderBy('waktu', 'desc');
        }
    
        // Filter berdasarkan tanggal spesifik (format: YYYY-MM-DD)
        if ($request->has('date') && !empty($request->date)) {
            $attendances->whereDate('waktu', $request->date);
        }
    
        // Filter berdasarkan bulan (format: YYYY-MM)
        if ($request->has('month') && !empty($request->month)) {
            $month = $request->month; // contoh: "2023-04"
            $attendances->whereYear('waktu', substr($month, 0, 4))
                        ->whereMonth('waktu', substr($month, 5, 2));
        }
    
        // Filter berdasarkan semester  
        // (diasumsikan: Semester 1 = Januari - Juni, Semester 2 = Juli - Desember)
        if ($request->has('semester') && !empty($request->semester)) {
            $semester = $request->semester;
            $year = date('Y'); // Anda bisa menyesuaikan dengan filter tahun jika perlu
            if ($semester == 1) {
                $attendances->whereYear('waktu', $year)
                            ->whereMonth('waktu', '>=', 1)
                            ->whereMonth('waktu', '<=', 6);
            } elseif ($semester == 2) {
                $attendances->whereYear('waktu', $year)
                            ->whereMonth('waktu', '>=', 7)
                            ->whereMonth('waktu', '<=', 12);
            }
        }
    
        if ($request->ajax()) {
            return datatables()
                ->of($attendances)
                ->addIndexColumn()
                ->addColumn('name', function ($attendance) {
                    if ($attendance->siswa) {
                        return $attendance->siswa->name;
                    } elseif ($attendance->guru) {
                        return $attendance->guru->name;
                    }
                    return 'N/A';
                })
                ->editColumn('waktu', function ($attendance) {
                    return \Carbon\Carbon::parse($attendance->waktu)->format('d M Y H:i');
                })
                ->editColumn('foto_wajah', function ($attendance) {
                    if ($attendance->foto_wajah) {
                        return '<img src="' . asset($attendance->foto_wajah) . '" alt="Foto Wajah" class="w-16 h-16 rounded-full object-cover">';
                    }
                    return 'N/A';
                })
                ->rawColumns(['foto_wajah'])
                ->make(true);
        }
    
        return view('attendances.history');
    }
    
        

}