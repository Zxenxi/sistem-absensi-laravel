<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Membuat Data Kelas ---
        // Kelas X (2 jurusan)
        $kelasXFarmasi = Kelas::create([
            'kelas'       => 'X',
            'jurusan'     => 'Farmasi',
            'tahun_ajaran'=> '2023/2024',
        ]);
        $kelasXTJKT = Kelas::create([
            'kelas'       => 'X',
            'jurusan'     => 'TJKT',
            'tahun_ajaran'=> '2023/2024',
        ]);

        // Kelas XI (5 jurusan)
        $kelasXIFarmasi = Kelas::create([
            'kelas'       => 'XI',
            'jurusan'     => 'Farmasi',
            'tahun_ajaran'=> '2023/2024',
        ]);
        $kelasXITJKT = Kelas::create([
            'kelas'       => 'XI',
            'jurusan'     => 'TJKT',
            'tahun_ajaran'=> '2023/2024',
        ]);
        $kelasXIPemasaran = Kelas::create([
            'kelas'       => 'XI',
            'jurusan'     => 'Pemasaran',
            'tahun_ajaran'=> '2023/2024',
        ]);
        $kelasXIAkuntansi = Kelas::create([
            'kelas'       => 'XI',
            'jurusan'     => 'Akuntansi',
            'tahun_ajaran'=> '2023/2024',
        ]);
        $kelasXIPerkantoran = Kelas::create([
            'kelas'       => 'XI',
            'jurusan'     => 'Perkantoran',
            'tahun_ajaran'=> '2023/2024',
        ]);

        // Kelas XII (5 jurusan)
        $kelasXIIFarmasi = Kelas::create([
            'kelas'       => 'XII',
            'jurusan'     => 'Farmasi',
            'tahun_ajaran'=> '2023/2024',
        ]);
        $kelasXIITJKT = Kelas::create([
            'kelas'       => 'XII',
            'jurusan'     => 'TJKT',
            'tahun_ajaran'=> '2023/2024',
        ]);
        $kelasXIIPemasaran = Kelas::create([
            'kelas'       => 'XII',
            'jurusan'     => 'Pemasaran',
            'tahun_ajaran'=> '2023/2024',
        ]);
        $kelasXIIAkuntansi = Kelas::create([
            'kelas'       => 'XII',
            'jurusan'     => 'Akuntansi',
            'tahun_ajaran'=> '2023/2024',
        ]);
        $kelasXIIPerkantoran = Kelas::create([
            'kelas'       => 'XII',
            'jurusan'     => 'Perkantoran',
            'tahun_ajaran'=> '2023/2024',
        ]);

        // Kumpulan ID kelas untuk penugasan siswa secara acak
        $kelasIds = [
            $kelasXFarmasi->id,
            $kelasXTJKT->id,
            $kelasXIFarmasi->id,
            $kelasXITJKT->id,
            $kelasXIPemasaran->id,
            $kelasXIAkuntansi->id,
            $kelasXIPerkantoran->id,
            $kelasXIIFarmasi->id,
            $kelasXIITJKT->id,
            $kelasXIIPemasaran->id,
            $kelasXIIAkuntansi->id,
            $kelasXIIPerkantoran->id,
        ];

        // --- Membuat Data User ---
        // 1. Admin
        User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@example.com',
            'role'              => 'admin',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Panggil GuruSeeder untuk membuat data guru sesuai daftar
        $this->call([
            GuruSeeder::class,
        ]);

        // Buat 20 Siswa dengan NISN berurutan (misal, dari 1000000001 hingga 1000000020)
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name'              => 'Siswa ' . $i,
                'email'             => 'siswa' . $i . '@example.com',
                'role'              => 'siswa',
                'password'          => Hash::make('password'),
                'kelas_id'          => $kelasIds[array_rand($kelasIds)],
                'nisn'              => (string)(1000000000 + $i),
                'email_verified_at' => now(),
            ]);
        }

        // Panggil seeder absensi untuk siswa (jika AttendanceSeeder untuk siswa sudah ada)
        $this->call([
            AttendanceSeeder::class, // pastikan Anda memiliki seeder ini untuk absensi siswa
            GuruSeeder::class,
        ]);
    }
}