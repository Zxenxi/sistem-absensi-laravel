<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user untuk siswa, admin, dan guru
        $siswaUser = User::create([
            'name' => 'saya siswa',
            'email' => 'falfatoni@gmail.com',
            'password' => Hash::make('toniman'),
            // role default 'siswa'
        ]);

        $adminUser = User::create([
            'name' => 'saya admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('toniman'),
            'email_verified_at' => now(),
        ]);

        $guruUser = User::create([
            'name' => 'saya guru mase',
            'email' => 'guru@gmail.com',
            'role' => 'guru',
            'password' => Hash::make('toniman'),
            'email_verified_at' => now(),
        ]);

        // Buat record kelas (contoh)
        $kelas1 = Kelas::create([
            'kelas' => 'x',
            'jurusan' => 'TJKT',
            'tahun_ajaran' => '2023/2024'
        ]);

        // Jika Anda memiliki seeder Kelas tersendiri, pastikan setidaknya ada satu record kelas.

        // Buat record siswa untuk user yang memiliki role siswa
        Siswa::create([
            'nisn' => '1234567890', // contoh NISN
            'nama' => $siswaUser->name,
            'kelas_id' => $kelas1->id,
            'user_id' => $siswaUser->id,
        ]);

        // Buat record guru untuk user yang memiliki role guru
        Guru::create([
            'nama' => $guruUser->name,
            'user_id' => $guruUser->id,
            // Jika ada kolom lain seperti foto, bisa diisi juga
        ]);

        // Jika Anda ingin menambahkan lebih banyak siswa/guru yang tidak terhubung dengan user,
        // Anda bisa panggil seeder lain atau membuat record tambahan di sini.
    }
}