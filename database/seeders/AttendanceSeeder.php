<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        // Ambil ID siswa dan guru
        $siswaIds = \App\Models\User::where('role', 'siswa')->pluck('id')->toArray();
        $guruIds  = \App\Models\User::where('role', 'guru')->pluck('id')->toArray();

        // Buat 70 record absensi untuk siswa
        for ($i = 0; $i < 70; $i++) {
            Attendance::create([
                'siswa_id'  => $faker->randomElement($siswaIds),
                'guru_id'   => null,
                'waktu'     => Carbon::now('Asia/Jakarta')->subDays(rand(0, 10))->toDateTimeString(),
                'status'    => $faker->randomElement(['Hadir', 'Terlambat']),
                'lokasi'    => $faker->address,
                'foto_wajah'=> null,
            ]);
        }
        // Buat 30 record absensi untuk guru
        for ($i = 0; $i < 30; $i++) {
            Attendance::create([
                'siswa_id'  => null,
                'guru_id'   => $faker->randomElement($guruIds),
                'waktu'     => Carbon::now('Asia/Jakarta')->subDays(rand(0, 10))->toDateTimeString(),
                'status'    => $faker->randomElement(['Hadir', 'Terlambat']),
                'lokasi'    => $faker->address,
                'foto_wajah'=> null,
            ]);
        }
    }
}