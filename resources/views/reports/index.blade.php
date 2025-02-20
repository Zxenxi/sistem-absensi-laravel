@extends('layouts.dashboard')

@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container grid px-6 mx-auto">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Export Laporan Absensi
            </h2>

            <!-- Informasi Penting -->
            <div class="p-4 mb-6 bg-purple-600 rounded-lg shadow-md text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                        <span>Pastikan data absensi telah diupdate sebelum melakukan export.</span>
                    </div>
                    <span class="font-semibold">Info Penting</span>
                </div>
            </div>
            <!-- Academic Year Selection -->
            <div class="mb-6">
                <label for="academic_year" class="block text-gray-700 dark:text-gray-300 mb-2">
                    Pilih Tahun Ajaran
                </label>
                <select id="academic_year"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-700 dark:text-gray-200">
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    <option value="2023/2024">2023/2024</option>
                    <option value="2024/2025">2024/2025</option>
                    <!-- Add more years as needed -->
                </select>
            </div>
            <!-- Export Sections -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Siswa Export Section -->
                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">
                        Laporan Siswa
                    </h2>
                    <div class="flex space-x-4">
                        <a id="exportSiswaPDF" href="#"
                            class="flex-1 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white py-2 px-4 rounded text-center">
                            Export PDF
                        </a>
                        <a id="exportSiswaExcel" href="#"
                            class="flex-1 bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white py-2 px-4 rounded text-center">
                            Export Excel
                        </a>
                    </div>
                </div>
                <!-- Guru Export Section -->
                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">
                        Laporan Guru
                    </h2>
                    <div class="flex space-x-4">
                        <a id="exportGuruPDF" href="#"
                            class="flex-1 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white py-2 px-4 rounded text-center">
                            Export PDF
                        </a>
                        <a id="exportGuruExcel" href="#"
                            class="flex-1 bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white py-2 px-4 rounded text-center">
                            Export Excel
                        </a>
                    </div>
                </div>
            </div>
            <!-- Additional Important Information -->
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">
                    Informasi Penting
                </h3>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300">
                    <li>Pastikan data absensi terbaru telah diupdate sebelum melakukan export.</li>
                    <li>Export laporan akan disesuaikan dengan tahun ajaran yang dipilih.</li>
                    <li>Untuk laporan siswa, data kelas dan jurusan akan disertakan dalam file export.</li>
                    <li>File PDF berformat dokumen cetak, sedangkan file Excel dapat diolah lebih lanjut.</li>
                </ul>
            </div>
        </div>
        </div>

        <script>
            // Update export links when the academic year is selected
            document.getElementById('academic_year').addEventListener('change', function() {
                var year = this.value;
                var baseSiswaPDF = "{{ route('export.siswa.pdf') }}";
                var baseSiswaExcel = "{{ route('export.siswa.excel') }}";
                var baseGuruPDF = "{{ route('export.guru.pdf') }}";
                var baseGuruExcel = "{{ route('export.guru.excel') }}";

                document.getElementById('exportSiswaPDF').href = baseSiswaPDF + "?academic_year=" + encodeURIComponent(
                    year);
                document.getElementById('exportSiswaExcel').href = baseSiswaExcel + "?academic_year=" +
                    encodeURIComponent(year);
                document.getElementById('exportGuruPDF').href = baseGuruPDF + "?academic_year=" + encodeURIComponent(
                    year);
                document.getElementById('exportGuruExcel').href = baseGuruExcel + "?academic_year=" +
                    encodeURIComponent(year);
            });
        </script>
    @endsection
