<?php

namespace App\Http\Controllers\Attendance;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        // Misalnya untuk tampilan admin: tampilkan data siswa
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

        // Buat direktori untuk menyimpan foto absensi jika belum ada
        $fotoDir = public_path('storage/absensi_foto');
        if (!file_exists($fotoDir)) {
            mkdir($fotoDir, 0777, true);
        }

        $fotoPath = null;
        if ($request->foto_wajah) {
            // Hapus prefix Base64 jika ada, lalu decode
            $fotoData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $request->foto_wajah));
            $fotoName = uniqid() . '.png';
            $fotoPath = 'storage/absensi_foto/' . $fotoName;
            file_put_contents(public_path($fotoPath), $fotoData);
        }

        if ($request->role === 'siswa') {
            // Coba ambil data siswa dari relasi Auth::user()->siswa
            $siswa = Auth::user()->siswa;
            if (!$siswa) {
                // Fallback: cari di tabel siswa berdasarkan user_id
                $siswa = Siswa::where('user_id', Auth::user()->id)->first();
            }
            if (!$siswa) {
                return response()->json([
                    'message' => 'Siswa tidak ditemukan. Pastikan akun Anda sudah terhubung dengan data siswa yang valid.'
                ], 404);
            }
            Attendance::create([
                'siswa_id'  => $siswa->id,
                'guru_id'   => null,
                'waktu'     => now(),
                'status'    => 'Hadir',
                'lokasi'    => $request->lokasi,
                'foto_wajah'=> $fotoPath,
            ]);
        } elseif ($request->role === 'guru') {
            // Coba ambil data guru dari relasi Auth::user()->guru
            $guru = Auth::user()->guru;
            if (!$guru) {
                // Fallback: cari di tabel guru berdasarkan user_id
                $guru = Guru::where('user_id', Auth::user()->id)->first();
            }
            if (!$guru) {
                return response()->json([
                    'message' => 'Guru tidak ditemukan. Pastikan akun Anda sudah terhubung dengan data guru yang valid.'
                ], 404);
            }
            Attendance::create([
                'guru_id'   => $guru->id,
                'siswa_id'  => null,
                'waktu'     => now(),
                'status'    => 'Hadir',
                'lokasi'    => $request->lokasi,
                'foto_wajah'=> $fotoPath,
            ]);
        }

        return response()->json(['message' => 'Absensi berhasil disimpan.']);
    }
}