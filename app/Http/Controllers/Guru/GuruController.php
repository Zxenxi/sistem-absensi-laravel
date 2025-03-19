<?php

namespace App\Http\Controllers\Guru;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class GuruController extends Controller
{
    // Menampilkan halaman dashboard guru (view)
    public function guru()
    {
        return view('dashboard.guru.index');
    }

    // Ambil semua data guru menggunakan Yajra DataTables
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'guru')->select('*');
            return DataTables::of($data)
                ->addIndexColumn() // Menambahkan kolom DT_RowIndex
                ->addColumn('action', function ($row) {
                    $btn  = '<button class="editGuru bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded" data-id="' . $row->id . '">Edit</button>';
                    $btn .= ' <button class="deleteGuru bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded" data-id="' . $row->id . '">Hapus</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // Jika bukan AJAX, kembalikan view dashboard guru
        return view('dashboard.guru.index');
    }

    // Tampilkan detail guru tertentu
    public function show($id)
    {
        $guru = User::where('role', 'guru')->find($id);
        if ($guru) {
            return response()->json([
                'success' => true,
                'data' => $guru,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Guru tidak ditemukan.',
        ], 404);
    }
    
    // Simpan data guru baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $guru = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru'
        ]);

        return response()->json(['success' => true, 'data' => $guru]);
    }

    // Perbarui data guru (mengubah nama, email, dan password)
    public function update(Request $request, $id)
    {
        $guru = User::where('role', 'guru')->find($id);
        if (!$guru) {
            return response()->json(['success' => false, 'message' => 'Guru tidak ditemukan.'], 404);
        }

        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6'
        ]);

        $updateData = [
            'name'  => $validatedData['name'],
            'email' => $validatedData['email'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $guru->update($updateData);

        return response()->json(['success' => true, 'data' => $guru]);
    }

    // Hapus data guru
    public function destroy($id)
    {
        $guru = User::where('role', 'guru')->find($id);
        if ($guru) {
            $guru->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Guru tidak ditemukan.'], 404);
    }
}