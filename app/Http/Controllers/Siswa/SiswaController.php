<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    // Tampilkan view data siswa (hanya user dengan role "siswa")
    public function siswa()
    {
        $siswa = User::where('role', 'siswa')->with('kelas')->get();
        return view('siswa.index', compact('siswa'));
    }

    // Mengembalikan data siswa dalam format JSON
    public function index()
    {
        $siswa = User::where('role', 'siswa')->with('kelas')->get();
        return response()->json(['siswa' => $siswa]);
    }

    // Method create mengembalikan opsi dropdown untuk kelas, jurusan, dan tahun ajaran
    public function create()
    {
        $kelas_options = Kelas::select('kelas')->distinct()->get();
        $jurusan_options = Kelas::select('jurusan')->distinct()->get();
        $tahun_ajaran_options = Kelas::select('tahun_ajaran')->distinct()->get();

        return response()->json([
            'kelas'         => $kelas_options,
            'jurusan'       => $jurusan_options,
            'tahun_ajaran'  => $tahun_ajaran_options
        ]);
    }

    // Simpan data siswa baru ke tabel users
    public function store(Request $request)
    {
        $request->validate([
            'nisn'         => 'required|unique:users,nisn',
            'name'         => 'required',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:6',
            'kelas'        => 'required|string',
            'jurusan'      => 'required|string',
            'tahun_ajaran' => 'required|string',
        ]);

        // Cari record kelas yang sesuai dengan pilihan dropdown
        $kelas_record = Kelas::where('kelas', $request->kelas)
            ->where('jurusan', $request->jurusan)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->first();

        if (!$kelas_record) {
            return response()->json(['message' => 'Data kelas tidak ditemukan.'], 404);
        }

        $siswa = User::create([
            'nisn'     => $request->nisn,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'siswa',
            'kelas_id' => $kelas_record->id,
        ]);

        return response()->json([
            'message' => 'Siswa created successfully',
            'siswa'   => $siswa
        ]);
    }

    // Tampilkan detail siswa dalam format JSON
    public function show(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return response()->json(['message' => 'Data bukan siswa.'], 404);
        }
        $siswa->load('kelas');
        return response()->json(['siswa' => $siswa]);
    }

    // Mengembalikan data siswa untuk form edit beserta opsi dropdown
    public function edit(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return response()->json(['message' => 'Data bukan siswa.'], 404);
        }
        $siswa->load('kelas');
        $kelas_options = Kelas::select('kelas')->distinct()->get();
        $jurusan_options = Kelas::select('jurusan')->distinct()->get();
        $tahun_ajaran_options = Kelas::select('tahun_ajaran')->distinct()->get();

        return response()->json([
            'siswa'               => $siswa,
            'kelas_options'       => $kelas_options,
            'jurusan_options'     => $jurusan_options,
            'tahun_ajaran_options'=> $tahun_ajaran_options
        ]);
    }

    // Perbarui data siswa
    public function update(Request $request, User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return response()->json(['message' => 'Data bukan siswa.'], 404);
        }

        $request->validate([
            'nisn'         => 'required|unique:users,nisn,' . $siswa->id,
            'name'         => 'required',
            'email'        => 'required|email|unique:users,email,' . $siswa->id,
            'kelas'        => 'required|string',
            'jurusan'      => 'required|string',
            'tahun_ajaran' => 'required|string',
        ]);

        $kelas_record = Kelas::where('kelas', $request->kelas)
            ->where('jurusan', $request->jurusan)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->first();

        if (!$kelas_record) {
            return response()->json(['message' => 'Data kelas tidak ditemukan.'], 404);
        }

        $siswa->update([
            'nisn'     => $request->nisn,
            'name'     => $request->name,
            'email'    => $request->email,
            'kelas_id' => $kelas_record->id,
        ]);

        return response()->json([
            'message' => 'Siswa updated successfully',
            'siswa'   => $siswa
        ]);
    }

    // Hapus data siswa
    public function destroy(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return response()->json(['message' => 'Data bukan siswa.'], 404);
        }
        $siswa->delete();
        return response()->json(['message' => 'Siswa deleted successfully']);
    }
}