<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class GuruSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        // Buat 5 guru
        for ($i = 0; $i < 5; $i++) {
            User::create([
                'name'     => $faker->name,
                'email'    => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role'     => 'guru',
            ]);
        }
    }
}