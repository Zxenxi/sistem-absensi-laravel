<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsAttendanceExport;
use App\Exports\TeachersAttendanceExport;
class ReportController extends Controller
{
    public function showExportForm()
    {
        return view('export');
    }

    // Memproses request export laporan (siswa dan guru dalam satu controller)
    public function exportReport(Request $request)
    {
        $reportType   = $request->input('report_type');    // 'siswa' atau 'guru'
        $academicYear = $request->input('academic_year');
        $format       = $request->input('format');         // 'pdf' atau 'excel'

        if ($reportType == 'siswa') {
            $class = $request->input('class');
            $major = $request->input('major');

            // Query absensi siswa dengan filter berdasarkan tahun ajaran, kelas, dan jurusan melalui relasi user.kelas
            $query = Attendance::whereNotNull('siswa_id')
                ->whereHas('siswa.kelas', function($q) use ($academicYear, $class, $major) {
                    $q->where('tahun_ajaran', $academicYear);
                    if ($class) {
                        $q->where('kelas', $class);
                    }
                    if ($major) {
                        $q->where('jurusan', $major);
                    }
                })->with('siswa.kelas');

            $attendances = $query->get();

            if ($format == 'pdf') {
                // Menggunakan view PDF untuk laporan siswa
                $pdf = PDF::loadView('reports.students_attendance', [
                    'attendances'  => $attendances,
                    'academicYear' => $academicYear,
                    'class'        => $class,
                    'major'        => $major,
                ]);
                return $pdf->download('laporan_absensi_siswa.pdf');
            } else { // format Excel
                return Excel::download(new StudentsAttendanceExport($academicYear, $class, $major), 'laporan_absensi_siswa.xlsx');
            }
        } else { // reportType == 'guru'
            // Query absensi guru
            $query = Attendance::whereNotNull('guru_id')->with('guru');
            $attendances = $query->get();

            if ($format == 'pdf') {
                // Menggunakan view PDF untuk laporan guru
                $pdf = PDF::loadView('reports.teachers_attendance', [
                    'attendances' => $attendances,
                ]);
                return $pdf->download('laporan_absensi_guru.pdf');
            } else {
                return Excel::download(new TeachersAttendanceExport, 'laporan_absensi_guru.xlsx');
            }
        }
    }
}