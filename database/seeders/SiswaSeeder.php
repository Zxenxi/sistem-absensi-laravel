<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Kelas;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch or create kelas records
        $kelasX_TJKT = Kelas::firstOrCreate(['kelas' => 'x', 'jurusan' => 'TJKT', 'tahun_ajaran' => '2023/2024']);
        $kelasXI_Farmasi = Kelas::firstOrCreate(['kelas' => 'xi', 'jurusan' => 'farmasi', 'tahun_ajaran' => '2023/2024']);
        $kelasXII_RPL = Kelas::firstOrCreate(['kelas' => 'xii', 'jurusan' => 'RPL', 'tahun_ajaran' => '2023/2024']);
        $kelasXIII_Akuntansi = Kelas::firstOrCreate(['kelas' => 'xiii', 'jurusan' => 'akuntansi', 'tahun_ajaran' => '2024/2025']);

        // Create siswa records
        Siswa::create([
            'nisn' => '1',
            'nama' => 'John Doe',
            'kelas_id' => $kelasX_TJKT->id,
        ]);

        Siswa::create([
            'nisn' => '2',
            'nama' => 'Jane Smith',
            'kelas_id' => $kelasXI_Farmasi->id,
        ]);

        Siswa::create([
            'nisn' => '3',
            'nama' => 'Alice Johnson',
            'kelas_id' => $kelasXII_RPL->id,
        ]);

        Siswa::create([
            'nisn' => '4',
            'nama' => 'Bob Brown',
            'kelas_id' => $kelasXIII_Akuntansi->id,
        ]);

        Siswa::create([
            'nisn' => '5',
            'nama' => 'Charlie Davis',
            'kelas_id' => $kelasX_TJKT->id,
        ]);

        Siswa::create([
            'nisn' => '6',
            'nama' => 'Diana Evans',
            'kelas_id' => $kelasXI_Farmasi->id,
        ]);

        Siswa::create([
            'nisn' => '7',
            'nama' => 'Ethan Wilson',
            'kelas_id' => $kelasXII_RPL->id,
        ]);

        Siswa::create([
            'nisn' => '8',
            'nama' => 'Fiona Martinez',
            'kelas_id' => $kelasXIII_Akuntansi->id,
        ]);
    }
}