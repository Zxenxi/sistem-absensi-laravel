<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class SiswaController extends Controller
{
    // Menampilkan view data siswa
    public function siswa()
    {
        return view('siswa.index');
    }

    // Mengembalikan data siswa untuk DataTables via AJAX
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $siswa = User::where('role', 'siswa')->with('kelas')->select('*');
            return DataTables::of($siswa)
                ->addColumn('kelas', function ($siswa) {
                    return $siswa->kelas
                        ? $siswa->kelas->kelas . " - " . $siswa->kelas->jurusan . " - " . $siswa->kelas->tahun_ajaran
                        : 'N/A';
                })
                ->addColumn('action', function ($siswa) {
                    $editBtn = '<button class="editSiswa bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded" data-id="' . $siswa->id . '">Edit</button>';
                    $deleteBtn = ' <button class="deleteSiswa bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded" data-id="' . $siswa->id . '">Hapus</button>';
                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('siswa.index');
    }

    // Mengembalikan opsi kelas untuk mengisi dropdown di modal tambah/edit
    public function create()
    {
        $kelas_options = Kelas::all();
        return response()->json(['kelas_options' => $kelas_options]);
    }

    // Simpan data siswa baru
    public function store(Request $request)
    {
        $request->validate([
            'nisn'      => 'required|unique:users,nisn',
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'kelas_id'  => 'required|exists:kelas,id',
        ]);

        $siswa = User::create([
            'nisn'      => $request->nisn,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'siswa',
            'kelas_id'  => $request->kelas_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Siswa created successfully',
            'siswa'   => $siswa,
        ]);
    }

    // Tampilkan detail siswa (jika diperlukan)
    public function show(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return response()->json(['success' => false, 'message' => 'Data bukan siswa.'], 404);
        }
        $siswa->load('kelas');
        return response()->json(['success' => true, 'siswa' => $siswa]);
    }

    // Kembalikan data siswa untuk form edit beserta opsi kelas
    public function edit(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return response()->json(['success' => false, 'message' => 'Data bukan siswa.'], 404);
        }
        $siswa->load('kelas');
        $kelas_options = Kelas::all();
        return response()->json([
            'success' => true,
            'siswa' => $siswa,
            'kelas_options' => $kelas_options,
        ]);
    }

    // Perbarui data siswa
    public function update(Request $request, User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return response()->json(['success' => false, 'message' => 'Data bukan siswa.'], 404);
        }

        $request->validate([
            'nisn'      => 'required|unique:users,nisn,' . $siswa->id,
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,' . $siswa->id,
            'kelas_id'  => 'required|exists:kelas,id',
        ]);

        $siswa->update([
            'nisn'      => $request->nisn,
            'name'      => $request->name,
            'email'     => $request->email,
            'kelas_id'  => $request->kelas_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Siswa updated successfully',
            'siswa'   => $siswa,
        ]);
    }

    // Hapus data siswa
    public function destroy(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            return response()->json(['success' => false, 'message' => 'Data bukan siswa.'], 404);
        }
        $siswa->delete();
        return response()->json(['success' => true, 'message' => 'Siswa deleted successfully']);
    }
}