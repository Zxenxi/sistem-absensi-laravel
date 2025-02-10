<?php

namespace App\Http\Controllers\Guru;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    // Tampilkan halaman dashboard guru (view)
    public function guru()
    {
        return view('dashboard.guru.index');
    }

    // Ambil semua data guru (users dengan role 'guru')
    public function index()
    {
        $data = User::where('role', 'guru')->get();
        return response()->json(['data' => $data]);
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

        // Validasi input; jika password diisi, harus memenuhi minimal 6 karakter
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6'
        ]);

        $updateData = [
            'name'  => $validatedData['name'],
            'email' => $validatedData['email'],
        ];

        // Jika password diisi, update password (hash password baru)
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