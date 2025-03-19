<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua ID kelas yang tersedia
        $kelasIds = Kelas::pluck('id')->toArray();

        // Jumlah siswa yang ingin di-seed, misalnya 20 siswa
        $jumlahSiswa = 20;

        for ($i = 1; $i <= $jumlahSiswa; $i++) {
            User::create([
                // Format NISN: misalnya 10 digit, diisi dengan angka berurutan (misalnya 0000000001, 0000000002, dst.)
                'nisn'      => str_pad($i, 10, '0', STR_PAD_LEFT),
                'name'      => 'Siswa' . $i,
                'email'     => 'siswa' . $i . '@example.com',
                'password'  => Hash::make('password'),
                'role'      => 'siswa',
                // Pilih kelas secara acak dari data kelas yang ada
                'kelas_id'  => $kelasIds[array_rand($kelasIds)],
            ]);
        }
    }
}