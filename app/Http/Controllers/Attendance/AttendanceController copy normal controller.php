<?php

namespace App\Http\Controllers\Attendance;

use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        $status = 'Hadir';

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
        
        // Jika jadwal piket ditemukan
        if ($piket) {
            // Jika jadwal memiliki waktu mulai dan waktu selesai
            if ($piket->start_time && $piket->end_time) {
                $start = Carbon::parse($piket->start_time, 'Asia/Jakarta');
                $end   = Carbon::parse($piket->end_time, 'Asia/Jakarta');
                // Jika waktu saat ini berada dalam rentang jadwal, tampilkan dashboard absensi
                if ($now->between($start, $end)) {
                    $attendances = Attendance::with(['siswa', 'guru'])
                        ->orderBy('waktu', 'desc')
                        ->get();
                    return view('attendances.dashboard', compact('attendances'));
                } else {
                    // Jika tidak, tampilkan form absensi biasa
                    return view('attendances.index');
                }
            } else {
                // Jika waktu tidak diisi, asumsikan guru bertugas penuh hari ini
                $attendances = Attendance::with(['siswa', 'guru'])
                    ->orderBy('waktu', 'desc')
                    ->get();
                return view('attendances.dashboard', compact('attendances'));
            }
        }
        
        // Jika tidak ada jadwal piket, tampilkan form absensi biasa
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
}