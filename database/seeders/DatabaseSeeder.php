<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminSeeder::class,      // Seeder untuk akun admin
            KelasSeeder::class,
            GuruSeeder::class,
            SiswaSeeder::class,
            AttendanceSeeder::class,
            PiketScheduleSeeder::class,
        ]);
    }
}