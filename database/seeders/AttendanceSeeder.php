<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AttendanceSeeder extends Seeder
{
    /**
     * Seed the application's attendance data.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Ambil semua user dengan role siswa dan guru
        $students = User::where('role', 'siswa')->get();
        $teachers = User::where('role', 'guru')->get();

        // Daftar status absensi yang mungkin
        $statuses = ['Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat'];

        // Buat 10 absensi untuk siswa
        foreach (range(1, 10) as $i) {
            // Pilih siswa secara acak
            $student = $students->random();

            Attendance::create([
                'siswa_id'  => $student->id,
                'guru_id'   => null,
                // Generate waktu acak dalam 7 hari terakhir antara pukul 07:00 dan 17:59
                'waktu'     => Carbon::now()->subDays(rand(0, 7))->setTime(rand(7, 17), rand(0, 59), rand(0, 59)),
                'status'    => $faker->randomElement($statuses),
                // Lokasi acak (latitude, longitude)
                'lokasi'    => $faker->latitude . ', ' . $faker->longitude,
                'foto_wajah'=> null,
            ]);
        }

        // Buat 10 absensi untuk guru
     
            $faker = Faker::create();
            // Ambil semua guru dari tabel users
            $teachers = User::where('role', 'guru')->get();
    
            // Untuk setiap guru, buat antara 1 hingga 5 data absensi acak
            foreach ($teachers as $teacher) {
                $attendanceCount = rand(1, 5);
                for ($i = 0; $i < $attendanceCount; $i++) {
                    Attendance::create([
                        'siswa_id'  => null, // Karena ini absensi guru
                        'guru_id'   => $teacher->id,
                        // Waktu acak dalam 7 hari terakhir antara pukul 07:00 sampai 17:59
                        'waktu'     => Carbon::now()->subDays(rand(0, 7))->setTime(rand(7, 17), rand(0, 59), rand(0, 59)),
                        'status'    => $faker->randomElement(['Hadir', 'Sakit', 'Izin', 'Alfa', 'Terlambat']),
                        'lokasi'    => $faker->latitude . ', ' . $faker->longitude,
                        'foto_wajah'=> null,
                    ]);
                }
            }
        
    }
}