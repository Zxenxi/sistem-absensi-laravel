@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header & Informasi Tambahan -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Riwayat Presensi</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-300">
                Selamat datang di halaman riwayat presensi. Di sini Anda dapat melihat catatan kehadiran dengan rinci.
                Gunakan filter di bawah ini untuk menyaring data berdasarkan hari, bulan, atau semester. Jika tidak ada
                filter,
                seluruh data akan ditampilkan.
            </p>
        </div>

        <!-- Filter Harian, Bulanan & Semester -->
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:space-x-4 space-y-2 md:space-y-0">
            <!-- Filter Harian -->
            <div class="flex items-center">
                <label for="date_filter" class="mr-2 text-gray-700 dark:text-gray-300">Filter Harian:</label>
                <input type="text" id="date_filter"
                    class="w-48 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100"
                    placeholder="Pilih tanggal">
            </div>
            <!-- Filter Bulanan -->
            <div class="flex items-center">
                <label for="month_filter" class="mr-2 text-gray-700 dark:text-gray-300">Filter Bulanan:</label>
                <input type="text" id="month_filter"
                    class="w-48 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100"
                    placeholder="Pilih bulan">
            </div>
            <!-- Filter Semester -->
            {{-- <div class="flex items-center">
                <label for="semester_filter" class="mr-2 text-gray-700 dark:text-gray-300">Filter Semester:</label>
                <select id="semester_filter"
                    class="w-48 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100">
                    <option value="">Pilih Semester</option>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>
            </div> --}}
            <!-- Tombol Clear -->
            <button id="clear-filters"
                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md whitespace-nowrap">
                Clear Filters
            </button>
        </div>

        <!-- Tabel Riwayat Presensi -->
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table id="attendance-table" class="min-w-full bg-white dark:bg-gray-800">
                <thead>
                    <tr>
                        <th
                            class="py-3 px-4 border-b bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                            No</th>
                        <th
                            class="py-3 px-4 border-b bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                            Nama</th>
                        <th
                            class="py-3 px-4 border-b bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                            Waktu</th>
                        <th
                            class="py-3 px-4 border-b bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                            Status</th>
                        <th
                            class="py-3 px-4 border-b bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                            Foto Wajah</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light">
                    {{-- Data akan di-load melalui AJAX --}}
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Sertakan Flatpickr dan plugin MonthSelect dari CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Flatpickr untuk filter harian
            var fpDaily = $('#date_filter').flatpickr({
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    table.ajax.reload();
                }
            });

            // Inisialisasi Flatpickr untuk filter bulanan dengan plugin MonthSelect
            var fpMonth = $('#month_filter').flatpickr({
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: "Y-m",
                        altFormat: "F Y"
                    })
                ],
                onChange: function(selectedDates, dateStr, instance) {
                    table.ajax.reload();
                }
            });

            // Reload DataTables ketika filter semester berubah
            $('#semester_filter').on('change', function() {
                table.ajax.reload();
            });

            // Tombol untuk menghapus semua filter
            $('#clear-filters').on('click', function() {
                fpDaily.clear();
                fpMonth.clear();
                $('#semester_filter').val('');
                table.ajax.reload();
            });

            // Inisialisasi DataTables dengan parameter tambahan untuk filter
            var table = $('#attendance-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('attendance.history') }}',
                    data: function(d) {
                        d.date = $('#date_filter').val(); // Filter harian
                        d.month = $('#month_filter').val(); // Filter bulanan
                        d.semester = $('#semester_filter').val(); // Filter semester
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'foto_wajah',
                        name: 'foto_wajah',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    searchPlaceholder: "Cari data...",
                    search: ""
                },
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
            });
        });
    </script>
@endsection
