<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $kelasData = [
            ['kelas' => 'X IPA', 'jurusan' => 'IPA', 'tahun_ajaran' => '2022/2023'],
            ['kelas' => 'XI IPA', 'jurusan' => 'IPA', 'tahun_ajaran' => '2022/2023'],
            ['kelas' => 'XII IPA', 'jurusan' => 'IPA', 'tahun_ajaran' => '2022/2023'],
            ['kelas' => 'X IPS', 'jurusan' => 'IPS', 'tahun_ajaran' => '2022/2023'],
            ['kelas' => 'XI IPS', 'jurusan' => 'IPS', 'tahun_ajaran' => '2022/2023'],
        ];

        foreach ($kelasData as $data) {
            Kelas::create($data);
        }
    }
}