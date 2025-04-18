{{-- resources/views/dashboard/content/index.blade.php --}}
@extends('layouts.dashboard') {{-- Assuming this layout handles sidebar, basic structure, and dark mode toggle --}}
@section('title', 'Dashboard Presensi SMK')

@push('styles')
    {{-- Include DataTables Tailwind CSS if you have it installed --}}
    {{-- <link rel="stylesheet" href="{{ asset('path/to/datatables-tailwind.css') }}"> --}}
    <style>
        /* More specific styling */
        body {
            font-family: 'Inter', sans-serif;
            /* Example using Inter font */
        }

        /* Define custom colors in tailwind.config.js for perfect match, otherwise use defaults */
        .bg-dashboard-bg {
            background-color: #f8f9fa;
        }

        .dark .bg-dashboard-bg {
            background-color: #111827;
        }

        /* Dark Gray 900 */

        .bg-card-bg {
            background-color: #ffffff;
        }

        .dark .bg-card-bg {
            background-color: #1f2937;
        }

        /* Dark Gray 800 */

        .text-heading {
            color: #1f2937;
        }

        /* Dark Gray 800 */
        .dark .text-heading {
            color: #f3f4f6;
        }

        /* Dark Gray 100 */

        .text-subtle {
            color: #6b7280;
        }

        /* Gray 500 */
        .dark .text-subtle {
            color: #9ca3af;
        }

        /* Dark Gray 400 */

        /* Style DataTables elements */
        .dataTables_wrapper .dataTables_length select {
            @apply bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500;
        }

        .dataTables_wrapper .dataTables_filter input {
            @apply bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply px-3 py-1 mx-1 rounded border border-gray-300 dark:border-gray-600 text-sm;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-blue-500 text-white border-blue-500;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            @apply bg-gray-100 dark:bg-gray-700;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            @apply opacity-50 cursor-not-allowed;
        }

        table.dataTable thead th {
            @apply whitespace-nowrap;
            /* Prevent header text wrapping */
        }
    </style>
@endpush


@section('content')
    {{-- Check if user is admin --}}
    @if (Auth::check() && Auth::user()->role === 'admin')
        {{-- Main content area container with background color --}}
        <div class="bg-dashboard-bg dark:bg-gray-900 min-h-full p-4 sm:p-6 lg:p-8">

            <header class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
                <div class="flex items-center space-x-4">
                    <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff' }}"
                        alt="{{ $user->name }}"
                        class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-white dark:border-gray-700 shadow-sm">
                    <div>
                        <h1 class="text-xl font-semibold text-heading dark:text-gray-100">{{ $user->name }}</h1>
                        <p class="text-sm text-subtle dark:text-gray-400">Admin / {{-- Add specific title if available --}} </p>
                    </div>
                </div>
                {{-- Navigation Tabs - Example --}}
                <nav
                    class="flex items-center space-x-4 sm:space-x-6 border border-gray-200 dark:border-gray-700 p-1 rounded-lg">
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-3 py-1.5 text-sm font-medium rounded-md bg-orange-100 text-orange-600 dark:bg-orange-700 dark:text-orange-100">Dashboard</a>
                    <a href="#"
                        class="px-3 py-1.5 text-sm font-medium text-subtle dark:text-gray-400 hover:text-heading dark:hover:text-gray-200 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">Documents</a>
                    <a href="#"
                        class="px-3 py-1.5 text-sm font-medium text-subtle dark:text-gray-400 hover:text-heading dark:hover:text-gray-200 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">Contacts</a>
                </nav>
            </header>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div
                    class="lg:col-span-1 bg-card-bg dark:bg-gray-800 p-6 rounded-xl shadow-sm transition-shadow hover:shadow-md">
                    <h2 class="font-semibold text-heading dark:text-gray-100 mb-4">Performance Insights</h2>
                    <div class="flex flex-col items-center">
                        <div class="relative w-36 h-36 mb-4">
                            <svg class="w-full h-full" viewBox="0 0 36 36">
                                <path class="text-gray-200 dark:text-gray-700" stroke-width="3" fill="none"
                                    stroke="currentColor"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831">
                                </path>
                                <path class="text-orange-500 dark:text-orange-400" stroke-width="3.5" fill="none"
                                    stroke="currentColor"
                                    stroke-dasharray="{{ number_format($attendancePercentage, 0) }}, 100"
                                    stroke-linecap="round"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831">
                                </path>
                            </svg>
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                                <span
                                    class="text-3xl font-bold text-heading dark:text-gray-100">{{ number_format($attendancePercentage, 0) }}%</span>
                                <span class="block text-xs text-subtle dark:text-gray-400 mt-1">Kehadiran Siswa</span>
                            </div>
                        </div>
                        {{-- Placeholder Toggles - Implement functionality if needed --}}
                        <div
                            class="flex space-x-4 text-sm mt-2 border-t border-gray-100 dark:border-gray-700 pt-4 w-full justify-center">
                            <button
                                class="text-subtle dark:text-gray-400 font-medium hover:text-heading dark:hover:text-gray-200">Hours
                                Worked</button>
                            <button
                                class="text-heading dark:text-gray-100 font-semibold border-b-2 border-gray-800 dark:border-gray-300 pb-0.5">Tasks
                                Completed</button>
                        </div>
                    </div>
                </div>

                <div
                    class="lg:col-span-2 bg-card-bg dark:bg-gray-800 p-6 rounded-xl shadow-sm transition-shadow hover:shadow-md">
                    <div class="flex flex-col sm:flex-row justify-between items-start mb-4">
                        <h2 class="font-semibold text-heading dark:text-gray-100 mb-2 sm:mb-0">Tren Kehadiran Mingguan</h2>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="text-xs sm:text-sm font-medium text-subtle dark:text-gray-400 inline-flex items-center">
                                <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Hadir/Izin:
                                <span
                                    class="text-xs bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded px-1.5 py-0.5 ml-1.5">{{ $todayAttendanceCount }}</span>
                            </span>
                            <span
                                class="text-xs sm:text-sm font-medium text-subtle dark:text-gray-400 inline-flex items-center">
                                <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.414L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Terlambat:
                                <span
                                    class="text-xs bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100 rounded px-1.5 py-0.5 ml-1.5">{{ $lateStudents }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="relative h-64 sm:h-72">
                        <canvas id="trendChart"></canvas> {{-- Removed w-full h-full as Chart.js handles sizing --}}
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-card-bg dark:bg-gray-800 p-4 rounded-xl shadow-sm">
                        <details> {{-- Use details/summary for collapsible filters on mobile --}}
                            <summary
                                class="text-md font-semibold text-heading dark:text-gray-100 cursor-pointer flex justify-between items-center">
                                Filter Presensi
                                <svg class="w-5 h-5 text-subtle dark:text-gray-400 transform transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </summary>
                            <div class="mt-4 space-y-4">
                                {{-- Student Filters --}}
                                <div>
                                    <h4 class="text-sm font-medium text-subtle dark:text-gray-400 mb-2">Filter Siswa</h4>
                                    <form id="filterForm" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                                        <div>
                                            <label for="jurusan"
                                                class="block text-xs font-medium text-subtle dark:text-gray-300">Jurusan</label>
                                            <select name="jurusan" id="jurusan"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="">Semua</option>
                                                @foreach ($jurusans as $jurusan)
                                                    <option value="{{ $jurusan->jurusan }}">{{ $jurusan->jurusan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="tahunAjaran"
                                                class="block text-xs font-medium text-subtle dark:text-gray-300">Th.
                                                Ajaran</label>
                                            <select name="tahunAjaran" id="tahunAjaran"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="">Semua</option>
                                                @foreach ($tahunAjarans as $tahun)
                                                    <option value="{{ $tahun->tahun_ajaran }}">{{ $tahun->tahun_ajaran }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="kelas"
                                                class="block text-xs font-medium text-subtle dark:text-gray-300">Kelas</label>
                                            <select name="kelas" id="kelas"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="">Semua</option>
                                                @foreach ($kelases as $kelas)
                                                    <option value="{{ $kelas->kelas }}">{{ $kelas->kelas }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                {{-- Teacher Filter --}}
                                <div>
                                    <h4
                                        class="text-sm font-medium text-subtle dark:text-gray-400 mb-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                                        Filter Guru</h4>
                                    <form id="filterTeacherForm" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                                        <div class="sm:col-span-2">
                                            <label for="namaGuru"
                                                class="block text-xs font-medium text-subtle dark:text-gray-300">Nama
                                                Guru</label>
                                            <select name="namaGuru" id="namaGuru"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="">Semua Guru</option>
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->name }}">{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- Empty div for alignment or add a reset button --}}
                                        <div></div>
                                    </form>
                                </div>
                            </div>
                        </details>
                    </div>

                    <div class="w-full overflow-hidden rounded-xl shadow-sm bg-card-bg dark:bg-gray-800 p-4">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-2">
                            <h3 class="text-lg font-semibold text-heading dark:text-gray-100">Presensi Siswa</h3>
                            <button id="refreshStudentTable" title="Refresh Data Siswa"
                                class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m-15.357-2a8.001 8.001 0 0115.357 2m0 0H15">
                                    </path>
                                </svg>
                                Refresh
                            </button>
                        </div>
                        {{-- Responsive Table Wrapper --}}
                        <div class="overflow-x-auto">
                            <table id="studentTable" class="min-w-full leading-normal text-sm" style="width:100%">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-semibold text-subtle dark:text-gray-300 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-semibold text-subtle dark:text-gray-300 uppercase tracking-wider">
                                            Siswa</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-semibold text-subtle dark:text-gray-300 uppercase tracking-wider">
                                            Kelas</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-semibold text-subtle dark:text-gray-300 uppercase tracking-wider">
                                            Jurusan</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-semibold text-subtle dark:text-gray-300 uppercase tracking-wider">
                                            Waktu</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-semibold text-subtle dark:text-gray-300 uppercase tracking-wider">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="text-heading dark:text-gray-200 divide-y divide-gray-100 dark:divide-gray-700">
                                    {{-- Data loaded via AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="w-full overflow-hidden rounded-xl shadow-sm bg-card-bg dark:bg-gray-800 p-4">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-2">
                            <h3 class="text-lg font-semibold text-heading dark:text-gray-100">Presensi Guru</h3>
                            <button id="refreshTeacherTable" title="Refresh Data Guru"
                                class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m-15.357-2a8.001 8.001 0 0115.357 2m0 0H15">
                                    </path>
                                </svg>
                                Refresh
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table id="teacherTable" class="min-w-full leading-normal text-sm" style="width:100%">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-semibold text-subtle dark:text-gray-300 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-semibold text-subtle dark:text-gray-300 uppercase tracking-wider">
                                            Guru</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-semibold text-subtle dark:text-gray-300 uppercase tracking-wider">
                                            Waktu</th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-semibold text-subtle dark:text-gray-300 uppercase tracking-wider">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="text-heading dark:text-gray-200 divide-y divide-gray-100 dark:divide-gray-700">
                                    {{-- Data loaded via AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 bg-gray-800 dark:bg-gray-800 text-white p-6 rounded-xl shadow-sm h-fit">
                    {{-- h-fit to match content height --}}
                    <div class="flex justify-between items-center mb-5">
                        <h2 class="font-semibold text-gray-100">Priority Actions</h2>
                        {{-- Heroicon: Ellipsis Vertical --}}
                        <button class="text-gray-400 hover:text-white focus:outline-none">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                            </svg>
                        </button>
                    </div>
                    {{-- This list is static based on the image. Make dynamic if needed. --}}
                    <ul class="space-y-4 text-sm">
                        <li class="flex justify-between items-center">
                            <span class="flex items-center text-gray-200"><span
                                    class="h-2 w-2 bg-green-500 rounded-full mr-2.5 flex-shrink-0"></span>Upload Proof of
                                Completion</span>
                            <span
                                class="text-xs font-medium bg-green-200 text-green-800 dark:bg-green-700 dark:text-green-100 px-2 py-0.5 rounded-full">New</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="flex items-center text-gray-200"><span
                                    class="h-2 w-2 bg-yellow-400 rounded-full mr-2.5 flex-shrink-0"></span>Approve Next
                                Month's Schedule</span>
                            <span
                                class="text-xs font-medium bg-yellow-200 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100 px-2 py-0.5 rounded-full">Pending</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="flex items-center text-gray-200"><span
                                    class="h-2 w-2 bg-yellow-400 rounded-full mr-2.5 flex-shrink-0"></span>Submit October
                                Work Report</span>
                            <span
                                class="text-xs font-medium bg-yellow-200 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100 px-2 py-0.5 rounded-full">Pending</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="flex items-center text-gray-200"><span
                                    class="h-2 w-2 bg-blue-500 rounded-full mr-2.5 flex-shrink-0"></span>Complete
                                Satisfaction Survey</span>
                            <span
                                class="text-xs font-medium bg-blue-200 text-blue-800 dark:bg-blue-700 dark:text-blue-100 px-2 py-0.5 rounded-full">In-Progress</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="flex items-center text-gray-200"><span
                                    class="h-2 w-2 bg-red-500 rounded-full mr-2.5 flex-shrink-0"></span>Approve Leave: Nov
                                20-25</span>
                            <span
                                class="text-xs font-medium bg-red-200 text-red-800 dark:bg-red-700 dark:text-red-100 px-2 py-0.5 rounded-full">Cancelled</span>
                        </li>
                    </ul>
                </div>
            </section>

            <section class="mb-8">
                <div
                    class="w-full rounded-xl shadow-sm bg-card-bg dark:bg-gray-800 p-4 md:p-6 transition-shadow hover:shadow-md">
                    <h4 class="mb-4 text-lg font-semibold text-heading dark:text-gray-100">Distribusi Absensi Hari Ini</h4>
                    <div class="relative h-72 sm:h-80">
                        <canvas id="pie"></canvas>
                    </div>
                </div>
            </section>


            <div class="mt-6 text-center text-subtle dark:text-gray-500">
                <p id="liveClock" class="text-sm"></p>
            </div>

        </div> {{-- End content area container --}}
    @else
        {{-- Content for non-admin users --}}
        <div class="container px-6 mx-auto grid py-6">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Dashboard
            </h2>
            <p class="text-gray-600 dark:text-gray-400">Welcome, {{ Auth::user()->name }}!</p>
            {{-- Add user-specific content here if needed --}}
            <div class="mt-4 p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                Your personalized dashboard content goes here.
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    {{-- Ensure jQuery, DataTables, Chart.js are loaded (ideally in the main layout) --}}
    {{-- Example using CDN for simplicity - Replace with your asset management --}}
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.css"/> --}}
    {{-- <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script> --}}

    <script>
        $(document).ready(function() {

            // Function to check if dark mode is enabled
            const isDarkMode = () => document.documentElement.classList.contains('dark');

            // --- DataTables Initialization ---
            const commonDataTableOptions = {
                processing: true,
                serverSide: true,
                responsive: false, // Let the overflow wrapper handle responsiveness
                // dom: 'lfrtip', // Default DOM structure
                // Adjust DOM for Tailwind styling (Length + Filter top, Table, Info + Pagination bottom)
                dom: "<'flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-3'<'text-sm'l><'text-sm'f>>" +
                    "<'w-full'tr>" +
                    "<'flex flex-col sm:flex-row sm:items-center sm:justify-between mt-3 gap-3'<'text-sm'i><'text-sm'p>>",
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari...",
                    lengthMenu: "Tampil _MENU_",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "<<",
                        last: ">>",
                        next: ">",
                        previous: "<"
                    },
                    processing: '<div class="text-center text-blue-500 my-4"><svg class="inline w-6 h-6 mr-2 animate-spin" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3.5a6.5 6.5 0 100 13 6.5 6.5 0 000-13zM10 18a8 8 0 110-16 8 8 0 010 16z" opacity=".3"></path><path d="M10 3.5V2a8 8 0 11-8 8h1.5a6.5 6.5 0 106.5-6.5z"></path></svg> Memproses...</div>',
                    emptyTable: "Tidak ada data tersedia"
                }
            };

            // Student Table
            if ($('#studentTable').length) {
                var studentTable = $('#studentTable').DataTable({
                    ...commonDataTableOptions, // Spread common options
                    ajax: {
                        url: "{{ route('admin.getStudentAttendances') }}",
                        data: function(d) {
                            // Append filter values
                            d.jurusan = $('#jurusan').val();
                            d.tahunAjaran = $('#tahunAjaran').val();
                            d.kelas = $('#kelas').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center w-10'
                        },
                        {
                            data: 'nama_siswa',
                            name: 'nama_siswa'
                        },
                        {
                            data: 'kelas',
                            name: 'kelas',
                            className: 'whitespace-nowrap'
                        },
                        {
                            data: 'jurusan',
                            name: 'jurusan'
                        },
                        {
                            data: 'waktu',
                            name: 'waktu',
                            className: 'whitespace-nowrap'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],
                    order: [
                        [4, 'desc']
                    ] // Default sort by time descending
                });

                // Trigger reload on filter change
                $('#jurusan, #tahunAjaran, #kelas').on('change', function() {
                    studentTable.ajax.reload();
                });
                $('#refreshStudentTable').click(function() {
                    studentTable.ajax.reload(null, false);
                });
            }

            // Teacher Table
            if ($('#teacherTable').length) {
                var teacherTable = $('#teacherTable').DataTable({
                    ...commonDataTableOptions, // Spread common options
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
                            searchable: false,
                            className: 'text-center w-10'
                        },
                        {
                            data: 'nama_guru',
                            name: 'nama_guru'
                        },
                        {
                            data: 'waktu',
                            name: 'waktu',
                            className: 'whitespace-nowrap'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],
                    order: [
                        [2, 'desc']
                    ] // Default sort by time descending
                });

                // Trigger reload on filter change
                $('#namaGuru').on('change', function() {
                    teacherTable.ajax.reload();
                });
                $('#refreshTeacherTable').click(function() {
                    teacherTable.ajax.reload(null, false);
                });
            }

            // --- Chart.js Initialization ---
            let attendanceChartInstance = null;
            let trendChartInstance = null;

            const chartTextColor = isDarkMode() ? '#d1d5db' : '#374151'; // Gray 300 dark, Gray 700 light
            const chartGridColor = isDarkMode() ? '#374151' : '#e5e7eb'; // Gray 700 dark, Gray 200 light
            const chartBorderColor = isDarkMode() ? '#374151' : '#ffffff'; // Card bg dark, white light

            // Pie Chart (Distribution)
            if ($('#pie').length && typeof Chart !== 'undefined') {
                const ctxPie = document.getElementById('pie').getContext('2d');
                attendanceChartInstance = new Chart(ctxPie, {
                    type: 'doughnut', // Doughnut looks slightly better for distribution
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            data: {!! json_encode($chartData) !!},
                            backgroundColor: [ // Use distinct, accessible colors
                                'rgb(59, 130, 246)', // blue-500
                                'rgb(245, 158, 11)', // amber-500
                                'rgb(139, 92, 246)', // violet-500
                                'rgb(239, 68, 68)', // red-500
                                'rgb(16, 185, 129)', // emerald-500 (extra)
                            ],
                            borderColor: chartBorderColor, // Match card background
                            borderWidth: 2,
                            hoverOffset: 8
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
                                position: 'bottom',
                                labels: {
                                    color: chartTextColor,
                                    padding: 15,
                                    usePointStyle: true,
                                    boxWidth: 8,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: ctx => `${ctx.label}: ${ctx.formattedValue}`
                                }
                            }
                        },
                        cutout: '60%' // Make it a doughnut chart
                    }
                });
            }

            // Line Chart (Trend)
            if ($('#trendChart').length && typeof Chart !== 'undefined') {
                const ctxTrend = document.getElementById('trendChart').getContext('2d');
                trendChartInstance = new Chart(ctxTrend, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($trendLabels) !!},
                        datasets: [{
                            label: 'Total Kehadiran',
                            data: {!! json_encode($trendData) !!},
                            borderColor: 'rgb(249, 115, 22)', // orange-500
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: 'rgb(249, 115, 22)',
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: chartGridColor,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: chartTextColor,
                                    font: {
                                        size: 11
                                    },
                                    padding: 8
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }, // Hide vertical grid lines
                                ticks: {
                                    color: chartTextColor,
                                    font: {
                                        size: 11
                                    },
                                    maxRotation: 0,
                                    autoSkipPadding: 10
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                titleFont: {
                                    weight: 'bold'
                                },
                                bodySpacing: 4,
                                // Custom tooltip appearance if desired
                                // backgroundColor: 'rgba(0,0,0,0.7)', titleColor: '#fff', bodyColor: '#fff',
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
            }

            // --- Live Clock ---
            const clockElement = document.getElementById('liveClock');

            function updateClock() {
                if (clockElement) {
                    const now = new Date();
                    const options = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    };
                    // Display date and time in Indonesian format
                    clockElement.textContent = now.toLocaleTimeString('id-ID', options);
                }
            }
            if (clockElement) {
                updateClock();
                setInterval(updateClock, 1000);
            }

            // Optional: Add listener for dark mode changes if your layout supports it
            // This requires a mechanism (e.g., MutationObserver or custom event) in your main layout
            // to detect when the 'dark' class is added/removed from <html> or <body>
            // Example:
            /*
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        const newTextColor = isDarkMode() ? '#d1d5db' : '#374151';
                        const newGridColor = isDarkMode() ? '#374151' : '#e5e7eb';
                        const newBorderColor = isDarkMode() ? '#1f2937' : '#ffffff'; // Card bg dark, white light

                        if (attendanceChartInstance) {
                            attendanceChartInstance.options.plugins.legend.labels.color = newTextColor;
                            attendanceChartInstance.data.datasets[0].borderColor = newBorderColor;
                            attendanceChartInstance.update();
                        }
                        if (trendChartInstance) {
                            trendChartInstance.options.plugins.legend.labels.color = newTextColor;
                            trendChartInstance.options.scales.y.grid.color = newGridColor;
                            trendChartInstance.options.scales.y.ticks.color = newTextColor;
                            trendChartInstance.options.scales.x.ticks.color = newTextColor;
                            trendChartInstance.update();
                        }
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
            */

        }); // End document ready
    </script>
@endsection
