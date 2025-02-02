<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'saya siswa',
            'email' => 'falfatoni@gmail.com',
            'password' => Hash::make('toniman'),
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'saya admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('toniman'),
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'saya guru mase',
            'email' => 'guru@gmail.com',
            'role' => 'guru',
            'password' => Hash::make('toniman'),
            'email_verified_at' => now(),
        ]);
    }
}