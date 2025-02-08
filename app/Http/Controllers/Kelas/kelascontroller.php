<?php

namespace App\Http\Controllers\Kelas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Tambahkan jika belum ada
use App\Models\Kelas; // Pastikan model Kelas ada

class KelasController extends Controller
{
    public function index()
    {
        return view('dashboard.kelas.index');
    }

    public function fetchKelas()
    {
        $kelas = Kelas::all();
        return response()->json(['success' => true, 'data' => $kelas]);
    }

    public function show($id)
    {
        $kelas = Kelas::find($id);
        if ($kelas) {
            return response()->json(['success' => true, 'data' => $kelas]);
        }
        return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:255',
        ]);

        $kelas = Kelas::create($validated);
        return response()->json(['success' => true, 'data' => $kelas]);
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::find($id);
        if (!$kelas) {
            return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'kelas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:255',
        ]);

        $kelas->update($validated);
        return response()->json(['success' => true, 'data' => $kelas]);
    }

    public function destroy($id)
    {
        $kelas = Kelas::find($id);
        if ($kelas) {
            $kelas->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
    }
}