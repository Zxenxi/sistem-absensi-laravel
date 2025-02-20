<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsAttendanceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $attendances = Attendance::whereNotNull('siswa_id')
            ->with('siswa.kelas')
            ->get();

        // Map the data to the desired columns
        $data = $attendances->map(function($attendance, $index) {
            return [
                'No'            => $index + 1,
                'NISN'          => $attendance->siswa->nisn ?? '',
                'Nama Siswa'    => $attendance->siswa->name ?? '',
                'Kelas'         => $attendance->siswa->kelas->kelas ?? '',
                'Jurusan'       => $attendance->siswa->kelas->jurusan ?? '',
                'Tahun Ajaran'  => $attendance->siswa->kelas->tahun_ajaran ?? '',
                'Waktu'         => $attendance->waktu,
                'Status'        => $attendance->status,
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'NISN',
            'Nama Siswa',
            'Kelas',
            'Jurusan',
            'Tahun Ajaran',
            'Waktu',
            'Status',
        ];
    }
}