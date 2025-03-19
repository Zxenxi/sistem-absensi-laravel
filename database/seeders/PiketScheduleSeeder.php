<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PiketSchedule;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PiketScheduleSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $guruIds = \App\Models\User::where('role', 'guru')->pluck('id')->toArray();

        // Buat 10 jadwal piket
        for ($i = 0; $i < 10; $i++) {
            $randomDate = Carbon::now()->addDays(rand(0, 30));
            PiketSchedule::create([
                'guru_id'       => $faker->randomElement($guruIds),
                'schedule_date' => $randomDate->toDateString(),
                'start_time'    => '07:00',
                'end_time'      => '15:00',
            ]);
        }
    }
}