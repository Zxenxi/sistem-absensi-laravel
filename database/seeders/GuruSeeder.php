<?php

namespace Database\Seeders;

use Nette\Schema\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gurus = [
            ['nama' => 'John Doe'],
            ['nama' => 'Jane Smith'],
            ['nama' => 'Alice Johnson'],
            ['nama' => 'Bob Brown'],
            ['nama' => 'Charlie Davis'],
        ];

        DB::table('guru')->insert($gurus);
    }
}