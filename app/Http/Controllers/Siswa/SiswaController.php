<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function siswa()
    {
        // $siswa = Siswa::with('kelas')->get(); // Ambil data siswa beserta kelas
        $siswa = Siswa::with('kelas')->get(); // Mengambil data siswa beserta relasi kelas
        return view('siswa.index', compact('siswa')); // Menampilkan view index.blade.php dengan data siswa
    }
    public function index()
    {
        $siswa = Siswa::with('kelas')->get(); // Mengambil data siswa beserta relasi kelas
        return response()->json(['siswa' => $siswa]); // Menampilkan view dengan data siswa
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::all(); // Ambil semua data kelas
        return response()->json(['kelas' => $kelas]); // Mengembalikan data kelas dalam format JSON untuk modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:siswa,nisn',
            'nama' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa = Siswa::create([
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
        ]);

        return response()->json(['message' => 'Siswa created successfully', 'siswa' => $siswa]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        return response()->json(['siswa' => $siswa]); // Menampilkan detail siswa dalam format JSON
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all(); // Ambil semua data kelas
        return response()->json(['siswa' => $siswa, 'kelas' => $kelas]); // Menampilkan siswa dan data kelas dalam format JSON untuk modal edit
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn' => 'required|unique:siswa,nisn,' . $siswa->id,
            'nama' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa->update([
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
        ]);

        return response()->json(['message' => 'Siswa updated successfully', 'siswa' => $siswa]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return response()->json(['message' => 'Siswa deleted successfully']);
    }
}