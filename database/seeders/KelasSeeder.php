<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kelas::create(['kelas' => 'x', 'jurusan' => 'TJKT', 'tahun_ajaran' => '2023/2024']);
        Kelas::create(['kelas' => 'xi', 'jurusan' => 'farmasi', 'tahun_ajaran' => '2023/2024']);
        Kelas::create(['kelas' => 'x', 'jurusan' => 'RPL', 'tahun_ajaran' => '2023/2024']);
        Kelas::create(['kelas' => 'xi', 'jurusan' => 'akuntansi', 'tahun_ajaran' => '2023/2024']);
        Kelas::create(['kelas' => 'xii', 'jurusan' => 'TJKT', 'tahun_ajaran' => '2023/2024']);
        Kelas::create(['kelas' => 'xii', 'jurusan' => 'farmasi', 'tahun_ajaran' => '2023/2024']);
        Kelas::create(['kelas' => 'xii', 'jurusan' => 'RPL', 'tahun_ajaran' => '2023/2024']);
        Kelas::create(['kelas' => 'xii', 'jurusan' => 'akuntansi', 'tahun_ajaran' => '2023/2024']);
        Kelas::create(['kelas' => 'xiii', 'jurusan' => 'TJKT', 'tahun_ajaran' => '2024/2025']);
        Kelas::create(['kelas' => 'xiii', 'jurusan' => 'farmasi', 'tahun_ajaran' => '2024/2025']);
        Kelas::create(['kelas' => 'xiii', 'jurusan' => 'RPL', 'tahun_ajaran' => '2024/2025']);
        Kelas::create(['kelas' => 'xiii', 'jurusan' => 'akuntansi', 'tahun_ajaran' => '2024/2025']);
    }
}