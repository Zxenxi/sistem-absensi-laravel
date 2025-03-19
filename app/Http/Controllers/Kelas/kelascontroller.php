<?php

namespace App\Http\Controllers\Kelas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Yajra\DataTables\Facades\DataTables;

class KelasController extends Controller
{
    public function index()
    {
        return view('dashboard.kelas.index');
    }

    public function fetchKelas()
    {
        // Ambil data kelas menggunakan query builder
        $kelas = Kelas::query();
        
        // Gunakan Yajra DataTables untuk mengembalikan data dalam format JSON
        return DataTables::of($kelas)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                // Buat tombol aksi untuk edit dan hapus
                $btn  = '<button onclick="editKelas('.$row->id.')" class="bg-blue-600 hover:bg-yellow-600 text-white px-4 py-2 rounded-md dark:bg-blue-700 dark:hover:bg-yellow-700 mr-2">Edit</button>';
                $btn .= '<button onclick="confirmDelete('.$row->id.')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md dark:bg-red-700 dark:hover:bg-red-800">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
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
        return response()->json(['success' => true, 'data' => $kelas, 'message' => 'Data kelas berhasil disimpan.']);
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
        return response()->json(['success' => true, 'data' => $kelas, 'message' => 'Data kelas berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $kelas = Kelas::find($id);
        if ($kelas) {
            $kelas->delete();
            return response()->json(['success' => true, 'message' => 'Data kelas berhasil dihapus.']);
        }
        return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
    }
}