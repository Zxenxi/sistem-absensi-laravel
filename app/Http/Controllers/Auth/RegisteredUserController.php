<?php

namespace App\Http\Controllers\Auth;

use App\Models\Guru;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
// use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use App\Providers\RouteServiceProvider;
    
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8', // Minimum 8 characters
                'confirmed', // Password confirmation required
            ],
            'role' => 'required|in:admin,guru,siswa',
            'nisn' => 'required_if:role,siswa|nullable|string|max:20',
        ]);

        // Cek apakah user dengan email yang sama sudah ada
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]
        );

        // Jika user baru dibuat, buat entitas tambahan (Guru atau Siswa)
        if ($user->wasRecentlyCreated) {
            if ($request->role === 'siswa') {
                Siswa::create([
                    'nisn' => $request->nisn,
                    'nama' => $request->name,
                    'kelas_id' => null, // Atur null dulu, admin bisa memperbarui nanti
                    'user_id' => $user->id,
                ]);
            } elseif ($request->role === 'guru') {
                Guru::create([
                    'nama' => $request->name,
                    'user_id' => $user->id,
                ]);
            }
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect berdasarkan role pengguna
        return match ($user->role) {
            'admin' => redirect('/admin/dashboard'),
            'guru' => redirect('/absensi'),
            'siswa' => redirect('/absensi'),
            default => redirect('/dashboard'),
        };
    }
}