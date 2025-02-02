<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create()
    {
        return view('auth.login'); // Pastikan view ini sesuai dengan struktur project Anda.
    }

    /**
     * Memproses autentikasi dan mengarahkan pengguna berdasarkan role.
     */
    public function store(Request $request)
    {
        // Validasi input email dan password.
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Lakukan autentikasi.
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Regenerasi session untuk menghindari session fixation.
        $request->session()->regenerate();

        $user = Auth::user();

        // Debug: pastikan nilai role sudah benar
        // dd($user->role);
        
        if ($user->role === 'admin') {
            return redirect()->to('/dashboard');
        } elseif (in_array($user->role, ['guru', 'siswa'])) {
            return redirect()->to('/absensi');
        } else {
            return redirect()->to('/dashboard');
        }
        
    }

    /**
     * Menangani proses logout.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}