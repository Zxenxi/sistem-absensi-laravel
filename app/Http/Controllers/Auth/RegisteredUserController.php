<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan tampilan registrasi.
     */
    public function create(): View
    {
        // Ambil data kelas untuk dropdown (jika diperlukan)
        $kelas_options = Kelas::all();
        return view('auth.register', compact('kelas_options'));
    }

    /**
     * Menangani permintaan registrasi.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input. Untuk siswa, pastikan NISN dan kelas_id diisi.
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'role'      => 'required|in:guru,siswa',
            'nisn'      => 'required_if:role,siswa|string|max:20',
            'kelas_id'  => 'required_if:role,siswa|exists:kelas,id',
        ]);

        // Buat user baru dan simpan data tambahan langsung di tabel users
        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'nisn'      => $data['role'] === 'siswa' ? $data['nisn'] : null,
            'kelas_id'  => $data['role'] === 'siswa' ? $data['kelas_id'] : null,
        ]);

        Log::info('User registered', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'role'    => $user->role,
        ]);

        // Jika di masa mendatang diperlukan proses tambahan untuk guru,
        // Anda bisa menambahkan kode di sini.

        event(new Registered($user));
        Auth::login($user);

        // Redirect: admin ke dashboard; guru dan siswa ke halaman absensi.
        if ($user->role === 'admin') {
            return redirect('/dashboard');
        } elseif (in_array($user->role, ['guru', 'siswa'])) {
            return redirect()->route('absensi.form'); // Pastikan route 'absensi.form' didefinisikan untuk tampilan absensi
        } else {
            return redirect('/absensi');
        }
    }
}