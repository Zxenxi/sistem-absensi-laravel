<?php

namespace App\Http\Controllers\Attendance;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    // Tampilkan halaman absensi
    public function index()
    {
        $siswa = Siswa::with('kelas')->get();
        return view('attendances.index', compact('siswa'));
    }

    // Simpan absensi
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:siswa,guru',
            'id' => 'required',
            'lokasi' => 'required|string',
            'foto_wajah' => 'required|string', // Foto dalam Base64
        ]);
    
        // Buat direktori untuk menyimpan foto jika belum ada
        $fotoDir = public_path('storage/absensi_foto');
        if (!file_exists($fotoDir)) {
            mkdir($fotoDir, 0777, true);
        }
    
        $fotoPath = null;
        if ($request->foto_wajah) {
            $fotoData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $request->foto_wajah));
            $fotoName = uniqid() . '.png';
            $fotoPath = 'storage/absensi_foto/' . $fotoName;
            file_put_contents(public_path($fotoPath), $fotoData);
        }
    
        if ($request->role === 'siswa') {
            $siswa = Siswa::where('nisn', $request->id)->first();
    
            if (!$siswa) {
                return response()->json(['message' => 'Siswa tidak ditemukan.'], 404);
            }
    
            Attendance::create([
                'siswa_id' => $siswa->id,
                'guru_id' => null, // Pastikan guru_id kosong
                'waktu' => now(),
                'status' => 'Hadir',
                'lokasi' => $request->lokasi,
                'foto_wajah' => $fotoPath,
            ]);
        } elseif ($request->role === 'guru') {
            $guru = Guru::where('nama', $request->id)->first();
    
            if (!$guru) {
                return response()->json(['message' => 'Guru tidak ditemukan.'], 404);
            }
    
            Attendance::create([
                'guru_id' => $guru->id,
                'siswa_id' => null, // Pastikan siswa_id kosong
                'waktu' => now(),
                'status' => 'Hadir',
                'lokasi' => $request->lokasi,
                'foto_wajah' => $fotoPath,
            ]);
        }
    
        return response()->json(['message' => 'Absensi berhasil disimpan.']);
    }
    
}