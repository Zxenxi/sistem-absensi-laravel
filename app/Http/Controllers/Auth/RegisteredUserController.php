<?php

namespace App\Http\Controllers\Auth;

use App\Models\Guru;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas; // Jika ingin mengaitkan siswa ke kelas default
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
 
        return view('auth.register');
    }

    /**
     * Menangani permintaan registrasi.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input. Pastikan 'role' harus dipilih secara eksplisit (default kosong)
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'role'      => 'required|in:guru,siswa',
            'nisn'      => 'required_if:role,siswa|nullable|string|max:20',
            // Jika role siswa ingin memilih kelas secara manual, aktifkan validasi berikut:
            // 'kelas_id' => 'required_if:role,siswa|exists:kelas,id',
        ]);

        // Log input registrasi untuk memastikan nilai role terkirim
        Log::info('Registration request received', $data);

        // Buat user baru menggunakan data input
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'], // Gunakan nilai yang dipilih di form
        ]);

        Log::info('User registered', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'role'    => $user->role, // Harus sesuai dengan input, bukan null
        ]);

        // Buat record tambahan berdasarkan role menggunakan relasi agar kolom user_id terisi otomatis
        if ($data['role'] === 'siswa') {
            // Gunakan kelas default jika input kelas tidak diberikan
            $kelasId = $request->input('kelas_id') ?? (Kelas::first() ? Kelas::first()->id : null);
            if (!$kelasId) {
                Log::error('Registration failed: no kelas available for siswa', ['user_id' => $user->id]);
                return redirect()->back()->withErrors([
                    'kelas_id' => 'Tidak ada kelas yang tersedia. Silakan tambahkan kelas terlebih dahulu.',
                ]);
            }
            $siswa = $user->siswa()->create([
                'nisn'     => $data['nisn'],
                'nama'     => $user->name,
                'kelas_id' => $kelasId,
            ]);
            Log::info('Siswa record created', [
                'user_id'  => $user->id,
                'siswa_id' => $siswa->id,
            ]);
        } elseif ($data['role'] === 'guru') {
            $guru = $user->guru()->create([
                'nama' => $user->name,
            ]);
            Log::info('Guru record created', [
                'user_id'  => $user->id,
                'guru_id'  => $guru->id,
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        // Redirect: admin ke dashboard; guru dan siswa ke halaman absensi.
        if ($user->role === 'admin') {
            return redirect('/dashboard');
        } elseif (in_array($user->role, ['guru', 'siswa'])) {
            return redirect()->route('absensi.store'); // Pastikan route 'absensi.form' didefinisikan
        } else {
            return redirect('/absensi');
        }
    }
}