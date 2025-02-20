<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Exportable;
class StudentsAttendanceExport implements FromCollection, WithHeadings
{
    
    protected $academicYear;
    protected $class;
    protected $major;

    public function __construct($academicYear, $class = null, $major = null)
    {
        $this->academicYear = $academicYear;
        $this->class        = $class;
        $this->major        = $major;
    }

    public function collection()
    {
        $query = Attendance::whereNotNull('siswa_id')
            ->whereHas('siswa.kelas', function($q) {
                $q->where('tahun_ajaran', $this->academicYear);
                if ($this->class) {
                    $q->where('kelas', $this->class);
                }
                if ($this->major) {
                    $q->where('jurusan', $this->major);
                }
            })->with('siswa.kelas');

        $attendances = $query->get();

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