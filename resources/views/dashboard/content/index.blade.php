@extends('layouts.dashboard')
@section('title', 'Dashboard Absensi SMK')

@section('content')
    @if (Auth::user()->role === 'admin')
        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Dashboard Absensi SMK</h2>

            <!-- Cards Informasi -->
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                <!-- Card Total Siswa -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-lg dark:bg-gray-800">
                    <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Siswa</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $totalSiswa }}</p>
                        <small class="text-xs text-gray-500">Data per {{ now()->format('H:i') }}</small>
                    </div>
                </div>
                <!-- Card Total Guru -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-lg dark:bg-gray-800">
                    <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Guru</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $totalGuru }}</p>
                        <small class="text-xs text-gray-500">Data per {{ now()->format('H:i') }}</small>
                    </div>
                </div>
                <!-- Card Kehadiran Hari Ini -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-lg dark:bg-gray-800">
                    <div
                        class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Kehadiran Hari Ini</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $todayAttendance }}</p>
                        <small class="text-xs text-gray-500">Jumlah hadir & terlambat</small>
                    </div>
                </div>
                <!-- Card Jadwal Piket Hari Ini -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-lg dark:bg-gray-800">
                    <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Jadwal Piket Hari Ini</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $todayPiket }}</p>
                        <small class="text-xs text-gray-500">Lihat jadwal piket</small>
                    </div>
                </div>
            </div>

            <!-- Info Tambahan -->
            <div class="grid gap-6 mb-8 md:grid-cols-2">
                <!-- Card Persentase Kehadiran -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-lg dark:bg-gray-800">
                    <div
                        class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Persentase Kehadiran</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $attendancePercentage }}%</p>
                        <small class="text-xs text-gray-500">Dari total siswa</small>
                    </div>
                </div>
                <!-- Card Siswa Terlambat -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-lg dark:bg-gray-800">
                    <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Siswa Terlambat</p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $lateStudents }}</p>
                        <small class="text-xs text-gray-500">Perhatikan keterlambatan</small>
                    </div>
                </div>
            </div>

            <!-- Tabel Absensi Siswa -->
            <div class="w-full overflow-x-auto rounded-lg shadow-lg bg-white dark:bg-gray-800 p-4 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Absensi Siswa Terbaru</h3>
                    <button id="refreshStudentTable"
                        class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                        Refresh
                    </button>
                </div>
                <table id="studentTable" class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <th
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                No</th>
                            <th
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Siswa</th>
                            <th
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Kelas</th>
                            <th
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Jurusan</th>
                            <th
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Waktu</th>
                            <th
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        <!-- Data dimuat lewat AJAX -->
                    </tbody>
                </table>
            </div>

            <!-- Tabel Absensi Guru -->
            <div class="w-full overflow-x-auto rounded-lg shadow-lg bg-white dark:bg-gray-800 p-4 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Absensi Guru Terbaru</h3>
                    <button id="refreshTeacherTable"
                        class=" px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                        Refresh
                    </button>
                </div>
                <table id="teacherTable" class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <th
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                No</th>
                            <th
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Guru</th>
                            <th
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Waktu</th>
                            <th
                                class="px-5 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            {{-- <th
                            class="px-5 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Lokasi</th> --}}
                        </tr>
                    </thead>
                    <tbody class="bg-white text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        <!-- Data dimuat lewat AJAX -->
                    </tbody>
                </table>
            </div>

            <!-- Chart Distribusi Absensi -->
            <div class="w-full rounded-lg shadow-lg bg-white dark:bg-gray-800 p-4">
                <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-300">Distribusi Absensi Hari Ini</h4>
                <div class="relative h-72">
                    <canvas id="pie" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Live Clock -->
            <div class="mt-6 text-center text-gray-600 dark:text-gray-400">
                <p id="liveClock" class="text-sm"></p>
            </div>
        </div>
    @endsection
@endif

@section('scripts')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- jQuery, DataTables, Chart.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            console.log('Initializing DataTables and Chart');

            // Initialize DataTable untuk Siswa
            var studentTable = $('#studentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.getStudentAttendances') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_siswa',
                        name: 'nama_siswa'
                    },
                    {
                        data: 'kelas',
                        name: 'kelas'
                    },
                    {
                        data: 'jurusan',
                        name: 'jurusan'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/English.json"
                },
                drawCallback: function(settings) {
                    console.log('Student DataTables drawCallback', settings.json);
                }
            });

            // Initialize DataTable untuk Guru
            var teacherTable = $('#teacherTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.getTeacherAttendances') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_guru',
                        name: 'nama_guru'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     data: 'lokasi',
                    //     name: 'lokasi',
                    //     searchable: false
                    // }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/English.json"
                },
                drawCallback: function(settings) {
                    console.log('Teacher DataTables drawCallback', settings.json);
                }
            });

            // Tombol Refresh untuk tabel
            $('#refreshStudentTable').click(function() {
                studentTable.ajax.reload(null, false);
            });
            $('#refreshTeacherTable').click(function() {
                teacherTable.ajax.reload(null, false);
            });

            // Inisialisasi Chart Distribusi Absensi
            if (window.attendanceChart) {
                window.attendanceChart.destroy();
            }
            var ctx = document.getElementById('pie').getContext('2d');
            window.attendanceChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: ['#3b82f6', '#14b8a6', '#8b5cf6', '#f87171']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: document.documentElement.classList.contains('dark') ? '#D1D5DB' :
                                    '#374151'
                            }
                        }
                    }
                }
            });

            // Live Clock
            function updateClock() {
                const now = new Date();
                const options = {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                $('#liveClock').text(now.toLocaleTimeString('id-ID', options));
            }
            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
@endsection
