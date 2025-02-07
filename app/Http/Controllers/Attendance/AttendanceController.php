<?php

namespace App\Http\Controllers\Attendance;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // untuk request API

class AttendanceController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with('kelas')->get();
        return view('attendances.index', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role'       => 'required|in:siswa,guru',
            'lokasi'     => 'required|string',
            'foto_wajah' => 'required|string', // Foto dalam format Base64
        ]);
        // Tentukan koordinat sekolah dan radius absensi
        
        $schoolLat =-7.709829747808012;   // Ganti dengan latitude sekolah Anda
        $schoolLng =110.0077439397974;   // Ganti dengan longitude sekolah Anda
        $allowedRadius = 60;      // Contoh: 100 meter (sesuaikan sesuai kebutuhan)

        // Parsing koordinat lokasi pengguna (format: "lat, lng")
        $lokasiUser = explode(',', $request->lokasi);
        if (count($lokasiUser) !== 2) {
            return response()->json([
                'message' => 'Format lokasi tidak valid. Pastikan formatnya: "latitude, longitude".'
            ], 400);
        }
        $userLat = floatval(trim($lokasiUser[0]));
        $userLng = floatval(trim($lokasiUser[1]));

        // Hitung jarak menggunakan fungsi Haversine dari helper
        $distance = calculateDistance($userLat, $userLng, $schoolLat, $schoolLng);
        if ($distance > $allowedRadius) {
            return response()->json([
                'message' => 'Absensi gagal: Anda berada di luar jangkauan absensi.'
            ], 403);
        }

        // Set zona waktu ke Asia/Jakarta (GMT+7) dan ambil waktu sekarang
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        // Tentukan batas waktu absensi tepat pukul 07:00 pagi
        $startTime = Carbon::parse($today . ' 07:00:00', 'Asia/Jakarta');
        $status = $now->gt($startTime) ? 'Terlambat' : 'Hadir';

        // Cek apakah hari ini merupakan akhir pekan (Sabtu/Minggu)
        if ($now->isWeekend()) {
            return response()->json([
                'message' => 'Hari ini adalah akhir pekan, absensi tidak dapat dilakukan.'
            ], 403);
        }

        // Ambil data hari libur menggunakan API Nager.Date
        $year = $now->year;
        $response = Http::get("https://date.nager.at/api/v3/PublicHolidays/{$year}/ID");
        if ($response->successful()) {
            $holidays = $response->json();
            $isHoliday = false;
            foreach ($holidays as $holiday) {
                if (isset($holiday['date']) && $holiday['date'] === $today) {
                    $isHoliday = true;
                    break;
                }
            }
            if ($isHoliday) {
                return response()->json([
                    'message' => 'Hari ini adalah hari libur nasional, absensi tidak dapat dilakukan.'
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'Gagal memeriksa hari libur. Silakan coba lagi nanti.'
            ], 500);
        }

        // Proses absensi berdasarkan role (siswa atau guru)
        if ($request->role === 'siswa') {
            $siswa = Auth::user()->siswa ?? Siswa::where('user_id', Auth::user()->id)->first();
            if (!$siswa) {
                return response()->json([
                    'message' => 'Siswa tidak ditemukan. Pastikan akun Anda sudah terhubung dengan data siswa yang valid.'
                ], 404);
            }
            // Cek apakah siswa sudah melakukan absensi hari ini
            $absenHariIni = Attendance::where('siswa_id', $siswa->id)
                ->whereDate('waktu', $today)
                ->first();
            if ($absenHariIni) {
                return response()->json([
                    'message' => 'Anda sudah melakukan absensi hari ini.'
                ], 403);
            }
            Attendance::create([
                'siswa_id'  => $siswa->id,
                'guru_id'   => null,
                'waktu'     => $now->toDateTimeString(),
                'status'    => $status,
                'lokasi'    => $request->lokasi,
                'foto_wajah'=> $this->saveFoto($request->foto_wajah),
            ]);
        } elseif ($request->role === 'guru') {
            $guru = Auth::user()->guru ?? Guru::where('user_id', Auth::user()->id)->first();
            if (!$guru) {
                return response()->json([
                    'message' => 'Guru tidak ditemukan. Pastikan akun Anda sudah terhubung dengan data guru yang valid.'
                ], 404);
            }
            // Cek apakah guru sudah melakukan absensi hari ini
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

    /**
     * Simpan foto absensi yang dikirim dalam format Base64 dan kembalikan path filenya.
     *
     * @param string $fotoBase64
     * @return string|null
     */
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