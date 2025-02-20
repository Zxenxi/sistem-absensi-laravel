<?php

namespace App\Http\Controllers\Report;

// use PDF;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsAttendanceExport;
use App\Exports\TeachersAttendanceExport;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }
    // Export Siswa (student) attendance to Excel
    public function exportSiswaExcel(Request $request)
    {
        return Excel::download(new StudentsAttendanceExport, 'laporan_absensi_siswa.xlsx');
    }

    // Export Siswa attendance to PDF
    public function exportSiswaPDF(Request $request)
    {
        // Retrieve attendance records for siswa (students) with related kelas data
        $attendances = Attendance::whereNotNull('siswa_id')
            ->with('siswa.kelas')
            ->get();

        $pdf = PDF::loadView('reports.siswa_pdf', compact('attendances'));
        return $pdf->download('laporan_absensi_siswa.pdf');
    }

    // Export Guru (teacher) attendance to Excel
    public function exportGuruExcel(Request $request)
    {
        return Excel::download(new TeachersAttendanceExport, 'laporan_absensi_guru.xlsx');
    }

    // Export Guru attendance to PDF
    public function exportGuruPDF(Request $request)
    {
        // Retrieve attendance records for guru (teachers)
        $attendances = Attendance::whereNotNull('guru_id')
            ->with('guru')
            ->get();

        $pdf = PDF::loadView('reports.guru_pdf', compact('attendances'));
        return $pdf->download('laporan_absensi_guru.pdf');
    }
}