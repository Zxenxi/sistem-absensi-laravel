<?php

namespace App\Http\Controllers\Auth;

use App\Models\Guru;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas; // Pastikan model Kelas diimpor jika diperlukan
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
    
class RegisteredUserController extends Controller
{
    /**
     * Menampilkan tampilan registrasi.
     */
    public function create(): View
    {
        // Jika diperlukan, Anda dapat mengirim data kelas ke view
        // $kelas = Kelas::all();
        // return view('auth.register', compact('kelas'));
        return view('auth.register');
    }

    /**
     * Menangani permintaan registrasi.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8', // minimal 8 karakter
                'confirmed',
            ],
            'role'     => 'required|in:admin,guru,siswa',
            'nisn'     => 'required_if:role,siswa|nullable|string|max:20',
            // Jika Anda ingin agar user memilih kelas secara manual, tambahkan validasi:
            // 'kelas_id' => 'required_if:role,siswa|exists:kelas,id',
        ]);

        // Buat user baru, jangan gunakan firstOrCreate agar tidak terjadi duplikasi atau penggunaan data lama
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Buat entitas tambahan berdasarkan role
        if ($request->role === 'siswa') {
            // Jika tidak ada input kelas dari form, ambil kelas default (misalnya kelas pertama)
            $kelasId = $request->input('kelas_id') ?: (Kelas::first() ? Kelas::first()->id : null);

            if (!$kelasId) {
                return redirect()->back()->withErrors([
                    'kelas_id' => 'Tidak ada kelas yang tersedia. Silakan tambahkan kelas terlebih dahulu.',
                ]);
            }

            Siswa::create([
                'nisn'     => $request->nisn,
                'nama'     => $request->name,
                'kelas_id' => $kelasId,
                'user_id'  => $user->id,
            ]);
        } elseif ($request->role === 'guru') {
            Guru::create([
                'nama'    => $request->name,
                'user_id' => $user->id,
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        // Redirect berdasarkan role user menggunakan named route untuk absensi
        if ($user->role === 'admin') {
            return redirect('/dashboard');
        } elseif (in_array($user->role, ['guru', 'siswa'])) {
            return redirect()->route('absensi.form');
        } else {
            return redirect('/absensi');
        }
    }
}