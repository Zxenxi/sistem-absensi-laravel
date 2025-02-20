<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersAttendanceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $attendances = Attendance::whereNotNull('guru_id')
            ->with('guru')
            ->get();

        $data = $attendances->map(function($attendance, $index) {
            return [
                'No'         => $index + 1,
                'Nama Guru'  => $attendance->guru->name ?? '',
                'Email'      => $attendance->guru->email ?? '',
                'Waktu'      => $attendance->waktu,
                'Status'     => $attendance->status,
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Guru',
            'Email',
            'Waktu',
            'Status',
        ];
    }
}