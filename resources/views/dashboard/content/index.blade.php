@extends('layouts.dashboard')
@section('title', 'Dashboard')

@section('content')
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Dashboard
        </h2>

        <!-- Cards Informasi -->
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
            <!-- Card Total Siswa -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Siswa</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $totalSiswa }}</p>
                </div>
            </div>
            <!-- Card Total Guru -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Guru</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $totalGuru }}</p>
                </div>
            </div>
            <!-- Card Kehadiran Hari Ini -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Kehadiran Hari Ini</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $todayAttendance }}</p>
                </div>
            </div>
            <!-- Card Jadwal Piket Hari Ini -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Jadwal Piket Hari Ini</p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $todayPiket }}</p>
                </div>
            </div>
        </div>

        <!-- Tabel Absensi Siswa Terbaru dengan DataTables -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs mt-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Absensi Siswa Terbaru</h3>
            <div class="w-full overflow-x-auto">
                <table id="studentTable" class="w-full whitespace-no-wrap border-collapse">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Siswa</th>
                            <th class="px-4 py-3">Kelas</th>
                            <th class="px-4 py-3">Jurusan</th>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestStudentAttendances as $attendance)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3">{{ $attendance->siswa->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $attendance->siswa->kelas->kelas ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $attendance->siswa->kelas->jurusan ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($attendance->waktu)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 font-semibold leading-tight rounded-full 
                                    @if ($attendance->status == 'Hadir') text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100
                                    @elseif($attendance->status == 'Sakit')
                                        text-orange-700 bg-orange-100 dark:bg-orange-700 dark:text-orange-100
                                    @elseif($attendance->status == 'Izin')
                                        text-blue-700 bg-blue-100 dark:bg-blue-700 dark:text-blue-100
                                    @else
                                        text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100 @endif">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $attendance->lokasi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Absensi Guru Terbaru dengan DataTables -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs mt-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Absensi Guru Terbaru</h3>
            <div class="w-full overflow-x-auto">
                <table id="teacherTable" class="w-full whitespace-no-wrap border-collapse">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Guru</th>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestTeacherAttendances as $attendance)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3">{{ $attendance->guru->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($attendance->waktu)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 font-semibold leading-tight rounded-full 
                                    @if ($attendance->status == 'Hadir') text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100
                                    @elseif($attendance->status == 'Sakit')
                                        text-orange-700 bg-orange-100 dark:bg-orange-700 dark:text-orange-100
                                    @elseif($attendance->status == 'Izin')
                                        text-blue-700 bg-blue-100 dark:bg-blue-700 dark:text-blue-100
                                    @else
                                        text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100 @endif">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $attendance->lokasi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart Distribusi Absensi Hari Ini -->
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 mt-6">
            <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">Distribusi Absensi Hari Ini</h4>
            <canvas id="pie" style="height: 300px;"></canvas>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Sertakan Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Sertakan jQuery dan DataTables JS (pastikan sudah di-include di layout jika belum) -->
    <script>
        $(document).ready(function() {
            $('#studentTable').DataTable({
                responsive: true,
                pageLength: 15,
                // Kolom "Kelas" dan "Jurusan" sudah otomatis bisa di-sort
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.1/i18n/English.json"
                }
            });
            $('#teacherTable').DataTable({
                responsive: true,
                pageLength: 15,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.1/i18n/English.json"
                }
            });
        });

        // Inisialisasi chart distribusi absensi
        var ctx = document.getElementById('pie').getContext('2d');
        var attendanceChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: ['#3b82f6', '#14b8a6', '#8b5cf6', '#f87171'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    </script>
@endsection
