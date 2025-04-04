@extends('layouts.dashboard')
@section('title', 'Dashboard Presensi SMK')

@section('content')
    @if (Auth::user()->role === 'admin')
        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Dashboard Presensi
            </h2>
            <!-- Cards Informasi -->
            <div class="grid gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Card Total Siswa -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800">
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
                <div class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800">
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
                <div class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800">
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
                <div class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800">
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

            <!-- Widget Info Tambahan -->
            <div class="grid gap-6 mb-8 sm:grid-cols-2">
                <!-- Widget Persentase Kehadiran -->
                <div class="flex flex-col p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                    <div class="flex items-center mb-2">
                        <div
                            class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full dark:text-purple-100 dark:bg-purple-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Persentase Kehadiran</p>
                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                {{ number_format($attendancePercentage, 2) }}%
                            </p>
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-green-500 h-2.5 rounded-full"
                            style="width: {{ number_format($attendancePercentage, 2) }}%"></div>
                    </div>
                    <small class="mt-1 text-xs text-gray-500">Dari total siswa</small>
                </div>
                <!-- Widget Siswa Terlambat -->
                <div class="flex flex-col p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                    <div class="flex items-center mb-2">
                        <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0H3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="mb-1 text-sm font-medium text-gray-600 dark:text-gray-400">Siswa Terlambat</p>
                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $lateStudents }}</p>
                        </div>
                    </div>
                    <small class="text-xs text-gray-500">Perhatikan keterlambatan</small>
                </div>
            </div>
            <!-- Form Filter untuk Siswa: Jurusan, Tahun Ajaran, & Kelas -->
            <div class="mb-6 text-black">
                <form id="filterForm" action="{{ route('admin.dashboard') }}" method="GET" class="flex space-x-4">
                    <div>
                        <label for="jurusan" class="block text-sm font-medium text-gray-700">Jurusan</label>
                        <select name="jurusan" id="jurusan"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua Jurusan</option>
                            @foreach ($jurusans as $jurusan)
                                <option value="{{ $jurusan->jurusan }}"
                                    {{ request('jurusan') == $jurusan->jurusan ? 'selected' : '' }}>
                                    {{ $jurusan->jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tahunAjaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                        <select name="tahunAjaran" id="tahunAjaran"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua Tahun Ajaran</option>
                            @foreach ($tahunAjarans as $tahun)
                                <option value="{{ $tahun->tahun_ajaran }}"
                                    {{ request('tahunAjaran') == $tahun->tahun_ajaran ? 'selected' : '' }}>
                                    {{ $tahun->tahun_ajaran }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                        <select name="kelas" id="kelas"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelases as $kelas)
                                <option value="{{ $kelas->kelas }}"
                                    {{ request('kelas') == $kelas->kelas ? 'selected' : '' }}>
                                    {{ $kelas->kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
            <!-- Tabel Absensi Siswa -->
            <div class="w-full overflow-x-auto rounded-lg shadow bg-white dark:bg-gray-800 p-4 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        Presensi Siswa Terbaru
                    </h3>
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

            <!-- Filter untuk Tabel Guru: Dropdown Berdasarkan Nama Guru -->
            <div class="mb-6 text-black">
                <div class="flex space-x-4">
                    <div>
                        <label for="namaGuru" class="block text-sm font-medium text-gray-700">Pilih Guru</label>
                        <select name="namaGuru" id="namaGuru"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua Guru</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->name }}"
                                    {{ request('namaGuru') == $teacher->name ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button id="filterTeacherBtn"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabel Absensi Guru -->
            <div class="w-full overflow-x-auto rounded-lg shadow bg-white dark:bg-gray-800 p-4 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        Presensi Guru Terbaru
                    </h3>
                    <button id="refreshTeacherTable"
                        class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
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
                        </tr>
                    </thead>
                    <tbody class="bg-white text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        <!-- Data dimuat lewat AJAX -->
                    </tbody>
                </table>
            </div>

            <!-- Chart Distribusi Absensi Hari Ini (Pie Chart) -->
            <div class="w-full rounded-lg shadow bg-white dark:bg-gray-800 p-4 mb-6">
                <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-300">Distribusi Absensi Hari Ini</h4>
                <div class="relative h-72">
                    <canvas id="pie" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Chart Tren Kehadiran Mingguan (Line Chart) -->
            <div class="w-full rounded-lg shadow bg-white dark:bg-gray-800 p-4 mb-6">
                <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-300">Tren Kehadiran Mingguan</h4>
                <div class="relative h-72">
                    <canvas id="trendChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Live Clock -->
            <div class="mt-6 text-center text-gray-600 dark:text-gray-400">
                <p id="liveClock" class="text-sm"></p>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <!-- DataTables CSS & JS, Chart.js -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            console.log('Initializing DataTables and Charts');

            // Inisialisasi DataTable untuk Siswa
            var studentTable = $('#studentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.getStudentAttendances') }}",
                    data: function(d) {
                        d.jurusan = $('#jurusan').val();
                        d.tahunAjaran = $('#tahunAjaran').val();
                        d.kelas = $('#kelas').val();
                    }
                },
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
                        orderable: false
                    } // Biarkan searchable default
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/English.json"
                },
                drawCallback: function(settings) {
                    console.log('Student Table drawn', settings.json);
                }
            });

            // Inisialisasi DataTable untuk Guru
            var teacherTable = $('#teacherTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.getTeacherAttendances') }}",
                    data: function(d) {
                        d.namaGuru = $('#namaGuru').val();
                    }
                },
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
                        orderable: false
                    } // Biarkan searchable default
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/English.json"
                },
                drawCallback: function(settings) {
                    console.log('Teacher Table drawn', settings.json);
                }
            });

            // Trigger reload DataTable saat filter pada siswa berubah
            $('#jurusan, #tahunAjaran, #kelas').on('change', function() {
                studentTable.ajax.reload();
            });

            // Trigger reload DataTable untuk guru saat dropdown filter berubah
            $('#namaGuru').on('change', function() {
                teacherTable.ajax.reload();
            });

            // Tombol refresh untuk tabel
            $('#refreshStudentTable').click(function() {
                studentTable.ajax.reload(null, false);
            });
            $('#refreshTeacherTable').click(function() {
                teacherTable.ajax.reload(null, false);
            });

            // Chart Distribusi (Pie Chart)
            var ctxPie = document.getElementById('pie').getContext('2d');
            window.attendanceChart = new Chart(ctxPie, {
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

            // Chart Tren (Line Chart)
            var ctxTrend = document.getElementById('trendChart').getContext('2d');
            var trendChart = new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: {!! json_encode($trendLabels) !!},
                    datasets: [{
                        label: 'Kehadiran',
                        data: {!! json_encode($trendData) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
