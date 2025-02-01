<?php

namespace App\Http\Controllers\Attendance;
use Carbon\Carbon;
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

    // Halaman absensi untuk guru (tanpa pilihan kelas)
    // public function teacherIndex()
    // {
    //     $guru = Guru::where('user_id', auth()->id())->first();
    //     if (!$guru) {
    //         abort(403, 'Unauthorized action.');
    //     }
    //     return view('attendance.index');
    //     // return view('attendance.teacher-attendance');
    // }

    // // Proses absensi guru
    // public function teacherMark(Request $request)
    // {
    //     $data = $request->validate([
    //         'latitude'  => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //         'selfie'    => 'required|string',  // Data foto dalam bentuk Base64
    //         'status'    => 'required|in:Hadir,Sakit,Izin,Alfa',
    //     ]);

    //     $guru = Guru::where('user_id', auth()->id())->first();
    //     if (!$guru) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     Attendance::create([
    //         'guru_id'    => $guru->id,
    //         'siswa_id'   => null,  // Guru absen sendiri
    //         'waktu'      => Carbon::now(),
    //         'lokasi'     => $data['latitude'] . ',' . $data['longitude'],
    //         'status'     => $data['status'],
    //         'foto_wajah' => $data['selfie'],
    //     ]);

    //     return response()->json(['message' => 'Attendance saved successfully']);
    // }

    // // Rekaman absensi siswa (untuk siswa melihat sendiri)
    // public function myAttendance(Request $request)
    // {
    //     // Misalnya, jika siswa melakukan absensi mandiri atau melihat rekaman absensi
    //     // Ambil data siswa berdasarkan user_id (pastikan kolom user_id ada di tabel siswa)
    //     $siswa = \App\Models\Siswa::where('user_id', auth()->id())->first();
    //     if (!$siswa) {
    //         abort(403, 'Unauthorized action.');
    //     }

    //     $records = Attendance::where('siswa_id', $siswa->id)->get();
    //     return view('attendance.my-attendance', compact('records'));
    // }


}