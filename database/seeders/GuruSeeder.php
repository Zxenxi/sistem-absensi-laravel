<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gurus = [
            ['nama' => 'John Doe', 'user_id' => null],
            ['nama' => 'Jane Smith', 'user_id' => null],
            ['nama' => 'Alice Johnson', 'user_id' => null],
            ['nama' => 'Bob Brown', 'user_id' => null],
            ['nama' => 'Charlie Davis', 'user_id' => null],
        ];

        DB::table('guru')->insert($gurus);
    }
}