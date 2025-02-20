@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h2>Export Laporan Absensi</h2>
        <form id="exportForm" method="GET" action="{{ route('export.report') }}">
            <!-- Pilih jenis laporan -->
            <div class="form-group">
                <label><strong>Pilih Jenis Laporan:</strong></label><br>
                <label>
                    <input type="radio" name="report_type" value="siswa" checked> Laporan Siswa
                </label>
                &nbsp;&nbsp;
                <label>
                    <input type="radio" name="report_type" value="guru"> Laporan Guru
                </label>
            </div>

            <!-- Pilih Tahun Ajaran -->
            <div class="form-group">
                <label for="academic_year"><strong>Pilih Tahun Ajaran:</strong></label>
                <select name="academic_year" id="academic_year" class="form-control" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    <!-- Sesuaikan opsi tahun ajaran dengan data Anda -->
                    <option value="2023/2024">2023/2024</option>
                    <option value="2024/2025">2024/2025</option>
                </select>
            </div>

            <!-- Opsi tambahan untuk Siswa -->
            <div id="siswaOptions">
                <div class="form-group">
                    <label for="class"><strong>Pilih Kelas:</strong></label>
                    <select name="class" id="class" class="form-control">
                        <option value="">-- Pilih Kelas --</option>
                        <!-- Contoh opsi kelas -->
                        <option value="X">X</option>
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="major"><strong>Pilih Jurusan:</strong></label>
                    <select name="major" id="major" class="form-control">
                        <option value="">-- Pilih Jurusan --</option>
                        <!-- Contoh opsi jurusan -->
                        <option value="IPA">IPA</option>
                        <option value="IPS">IPS</option>
                        <option value="Bahasa">Bahasa</option>
                    </select>
                </div>
            </div>

            <!-- Pilih format export -->
            <div class="form-group">
                <label><strong>Pilih Format Export:</strong></label><br>
                <label>
                    <input type="radio" name="format" value="pdf" checked> PDF
                </label>
                &nbsp;&nbsp;
                <label>
                    <input type="radio" name="format" value="excel"> Excel
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Export Laporan</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk toggle opsi tambahan untuk siswa
            function toggleSiswaOptions() {
                var reportType = document.querySelector('input[name="report_type"]:checked').value;
                var siswaOptions = document.getElementById('siswaOptions');
                if (reportType === 'siswa') {
                    siswaOptions.style.display = 'block';
                } else {
                    siswaOptions.style.display = 'none';
                }
            }

            // Bind change event ke radio button jenis laporan
            var reportTypeRadios = document.querySelectorAll('input[name="report_type"]');
            reportTypeRadios.forEach(function(radio) {
                radio.addEventListener('change', toggleSiswaOptions);
            });

            // Inisialisasi tampilan
            toggleSiswaOptions();
        });
    </script>
@endsection
