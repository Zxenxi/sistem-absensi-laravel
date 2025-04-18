<!DOCTYPE html>
<html lang="id" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark',
    init() {
        this.darkMode = localStorage.getItem('theme') === 'dark';
        this.$watch('darkMode', val => {
            localStorage.setItem('theme', val ? 'dark' : 'light');
            if (val) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            // Panggil update tema chart setelah DOM siap dan chart diinisialisasi
            if (typeof updateAllChartThemes === 'function') {
                setTimeout(updateAllChartThemes, 50); // Beri sedikit jeda
            }
        });
        // Terapkan tema awal saat init
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Penabur Presensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script type="module" src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* gray-100 */
            border-radius: 10px;
        }

        html.dark ::-webkit-scrollbar-track {
            background: #1f2937;
            /* gray-800 */
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            /* slate-300 */
            border-radius: 10px;
        }

        html.dark ::-webkit-scrollbar-thumb {
            background: #4b5563;
            /* gray-600 */
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
            /* slate-400 */
        }

        html.dark ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
            /* gray-500 */
        }

        /* Gaya item navigasi atas yang aktif */
        .top-nav-item.active {
            background-color: #eef2ff;
            /* indigo-50 */
            color: #4f46e5;
            /* indigo-600 */
            font-weight: 600;
        }

        html.dark .top-nav-item.active {
            background-color: #3730a3;
            /* indigo-800 */
            color: #e0e7ff;
            /* indigo-200 */
        }

        /* Gaya default item navigasi atas */
        .top-nav-item {
            color: #6b7280;
            /* gray-500 */
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        html.dark .top-nav-item {
            color: #d1d5db;
            /* gray-300 */
        }

        .top-nav-item:hover {
            background-color: #f3f4f6;
            /* gray-100 */
            color: #1f2937;
            /* gray-800 */
        }

        html.dark .top-nav-item:hover {
            background-color: #374151;
            /* gray-700 */
            color: #ffffff;
        }

        /* Gaya untuk item navigasi bawah yang aktif (mobile) */
        .bottom-nav-item.active {
            color: #4f46e5;
            /* indigo-600 */
        }

        html.dark .bottom-nav-item.active {
            color: #818cf8;
            /* indigo-400 */
        }

        /* Sembunyikan elemen dengan x-cloak saat AlpineJS memuat */
        [x-cloak] {
            display: none !important;
        }

        /* Progress bar styling */
        .progress-bar {
            background-color: #e5e7eb;
            /* gray-200 */
            border-radius: 9999px;
            overflow: hidden;
            height: 6px;
        }

        html.dark .progress-bar {
            background-color: #4b5563;
            /* gray-600 */
        }

        .progress-fill {
            background-color: #818cf8;
            /* indigo-400 */
            height: 100%;
            border-radius: 9999px;
            transition: width 0.5s ease;
        }

        /* Pastikan canvas chart responsif */
        canvas {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased font-sans" x-data="{ isMobileMenuOpen: false, isUserMenuOpen: false }"
    x-init="init()">
    <div class="flex flex-col min-h-screen">
        <header
            class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-30 border-b border-gray-200 dark:border-gray-700"
            @click.away="isUserMenuOpen = false">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative flex justify-between items-center h-16">
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="h-8 w-auto text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-800 dark:text-white hidden sm:inline">
                            Penabur Presensi
                        </span>
                    </div>

                    <div class="hidden md:flex md:absolute md:inset-y-0 md:left-1/2 md:transform md:-translate-x-1/2">
                        <nav class="flex space-x-2">
                            <a href="#"
                                class="top-nav-item active flex items-center space-x-2 px-3 py-2 rounded-md text-sm"
                                aria-current="page">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                <span>Dashboard</span></a>
                            <a href="#"
                                class="top-nav-item flex items-center space-x-2 px-3 py-2 rounded-md text-sm">
                                <i data-lucide="users" class="w-4 h-4"></i>
                                <span>Manajemen Pengguna</span></a>
                            <a href="#"
                                class="top-nav-item flex items-center space-x-2 px-3 py-2 rounded-md text-sm">
                                <i data-lucide="building" class="w-4 h-4"></i>
                                <span>Manajemen Kelas</span></a>
                            <a href="#"
                                class="top-nav-item flex items-center space-x-2 px-3 py-2 rounded-md text-sm">
                                <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                                <span>Laporan</span></a>
                        </nav>
                    </div>

                    <div
                        class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                        <div class="flex items-center space-x-3">
                            <button type="button" title="Cari"
                                class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                <span class="sr-only">Cari</span>
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </button>

                            <button @click="darkMode = !darkMode" type="button"
                                :title="darkMode ? 'Mode Terang' : 'Mode Gelap'"
                                class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                <span class="sr-only">Toggle Dark Mode</span>
                                <i x-show="!darkMode" data-lucide="moon" class="w-5 h-5"></i>
                                <i x-show="darkMode" data-lucide="sun" class="w-5 h-5" x-cloak></i>
                            </button>

                            <button type="button" title="Notifikasi"
                                class="relative text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                <span class="sr-only">Notifikasi</span>
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                <span
                                    class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-1 ring-white dark:ring-gray-800"></span>
                            </button>

                            <div class="relative">
                                <button @click="isUserMenuOpen = !isUserMenuOpen" type="button"
                                    class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Buka menu user</span>
                                    <img class="h-8 w-8 rounded-full object-cover ring-1 ring-gray-300 dark:ring-gray-600"
                                        src="https://placehold.co/100x100/6366F1/FFFFFF?text=A" alt="Admin Avatar"
                                        onerror="this.onerror=null; this.src='https://placehold.co/100x100/cccccc/ffffff?text=Err';" />
                                    <span
                                        class="hidden md:block ml-2 text-sm font-medium text-gray-700 dark:text-gray-200">Admin
                                        User</span>
                                    <i data-lucide="chevron-down"
                                        class="hidden md:block ml-1 h-4 w-4 text-gray-400"></i>
                                </button>
                                <div x-show="isUserMenuOpen" x-cloak
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                    tabindex="-1">
                                    <a href="#"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600"
                                        role="menuitem" tabindex="-1">
                                        <i data-lucide="user-circle" class="w-4 h-4 mr-2"></i>
                                        Profil Anda</a>
                                    <a href="#"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600"
                                        role="menuitem" tabindex="-1">
                                        <i data-lucide="settings" class="w-4 h-4 mr-2"></i>
                                        Pengaturan</a>
                                    <a href="#"
                                        class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600"
                                        role="menuitem" tabindex="-1">
                                        <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                                        Keluar</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="absolute inset-y-0 right-0 flex items-center md:hidden">
                        <button @click="isMobileMenuOpen = !isMobileMenuOpen" id="mobile-menu-button"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                            aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Buka menu utama</span>
                            <i x-show="!isMobileMenuOpen" data-lucide="menu" class="block h-6 w-6"></i>
                            <i x-show="isMobileMenuOpen" data-lucide="x" class="block h-6 w-6" x-cloak></i>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="isMobileMenuOpen" x-cloak class="md:hidden border-t border-gray-200 dark:border-gray-700"
                id="mobile-menu" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="#"
                        class="flex items-center space-x-2 bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 block px-3 py-2 rounded-md text-base font-medium"
                        aria-current="page">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span>Dashboard</span></a>
                    <a href="#"
                        class="flex items-center space-x-2 text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>Manajemen Pengguna</span></a>
                    <a href="#"
                        class="flex items-center space-x-2 text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                        <i data-lucide="building" class="w-5 h-5"></i>
                        <span>Manajemen Kelas</span></a>
                    <a href="#"
                        class="flex items-center space-x-2 text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                        <span>Laporan</span></a>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto pb-16 md:pb-0">
            <div class="p-4 sm:p-6 lg:p-8 space-y-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                            Halo Admin!
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Selamat datang kembali, semoga harimu menyenangkan.
                        </p>
                    </div>
                    <div class="flex items-center space-x-2 flex-wrap gap-y-2">
                        <button title="Filter"
                            class="p-2 rounded-lg border bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm">
                            <i data-lucide="sliders-horizontal" class="w-5 h-5"></i>
                        </button>
                        <select
                            class="text-sm rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option>Semua Kelas</option>
                            <option>Kelas 10</option>
                            <option>Kelas 11</option>
                            <option>Kelas 12</option>
                        </select>
                        <select
                            class="text-sm rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            <option>30 Hari Terakhir</option>
                            <option>7 Hari Terakhir</option>
                            <option>Bulan Ini</option>
                            <option>Tahun Ini</option>
                        </select>
                        <button title="Pilih Tanggal"
                            class="p-2 rounded-lg border bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm">
                            <i data-lucide="calendar-days" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Siswa
                            </h3>
                            <span class="flex-shrink-0 p-1.5 rounded-full bg-indigo-100 dark:bg-indigo-900/50">
                                <i data-lucide="users" class="h-5 w-5 text-indigo-600 dark:text-indigo-400"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            1250
                        </p>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Hadir Hari Ini
                            </h3>
                            <span class="flex-shrink-0 p-1.5 rounded-full bg-green-100 dark:bg-green-900/50">
                                <i data-lucide="user-check" class="h-5 w-5 text-green-600 dark:text-green-400"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            1195
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            <i data-lucide="arrow-up-right" class="inline w-3 h-3"></i>
                            95.6%
                        </p>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Absen Hari Ini
                            </h3>
                            <span class="flex-shrink-0 p-1.5 rounded-full bg-red-100 dark:bg-red-900/50">
                                <i data-lucide="user-x" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">55</p>
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                            <i data-lucide="arrow-down-right" class="inline w-3 h-3"></i>
                            4.4%
                        </p>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Terlambat Hari Ini
                            </h3>
                            <span class="flex-shrink-0 p-1.5 rounded-full bg-yellow-100 dark:bg-yellow-900/50">
                                <i data-lucide="clock" class="w-5 h-5 text-yellow-600 dark:text-yellow-400"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">25</p>
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                            <i data-lucide="alert-triangle" class="inline w-3 h-3"></i> 2.0%
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div
                        class="lg:col-span-2 bg-white dark:bg-gray-800 p-5 sm:p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                Laporan Kehadiran Total
                            </h3>
                            <button title="Opsi"
                                class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="more-vertical" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <div class="h-72 relative">
                            <canvas id="totalAttendanceChart"></canvas>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 p-5 sm:p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                Siswa Berdasarkan Gender
                            </h3>
                            <button title="Opsi"
                                class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="more-vertical" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <div class="h-72 flex justify-center items-center relative">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div
                        class="bg-white dark:bg-gray-800 p-5 sm:p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                5 Siswa Teratas Hadir
                            </h3>
                            <button title="Opsi"
                                class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="more-vertical" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <ul class="space-y-4">
                            <li class="flex items-center space-x-3">
                                <img class="h-10 w-10 rounded-full object-cover flex-shrink-0 ring-1 ring-gray-200 dark:ring-gray-600"
                                    src="https://placehold.co/100x100/7C3AED/FFFFFF?text=AF" alt="Ahmad Fauzi" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        Ahmad Fauzi
                                    </p>
                                    <div class="progress-bar mt-1">
                                        <div class="progress-fill" style="width: 100%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">100%</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <img class="h-10 w-10 rounded-full object-cover flex-shrink-0 ring-1 ring-gray-200 dark:ring-gray-600"
                                    src="https://placehold.co/100x100/EC4899/FFFFFF?text=SN" alt="Siti Nurhaliza" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        Siti Nurhaliza
                                    </p>
                                    <div class="progress-bar mt-1">
                                        <div class="progress-fill" style="width: 98%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">98%</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <img class="h-10 w-10 rounded-full object-cover flex-shrink-0 ring-1 ring-gray-200 dark:ring-gray-600"
                                    src="https://placehold.co/100x100/F87171/FFFFFF?text=DL" alt="Dewi Lestari" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        Dewi Lestari
                                    </p>
                                    <div class="progress-bar mt-1">
                                        <div class="progress-fill" style="width: 97%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">97%</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <img class="h-10 w-10 rounded-full object-cover flex-shrink-0 ring-1 ring-gray-200 dark:ring-gray-600"
                                    src="https://placehold.co/100x100/3B82F6/FFFFFF?text=RP" alt="Rizky Pratama" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        Rizky Pratama
                                    </p>
                                    <div class="progress-bar mt-1">
                                        <div class="progress-fill" style="width: 97%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">97%</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <img class="h-10 w-10 rounded-full object-cover flex-shrink-0 ring-1 ring-gray-200 dark:ring-gray-600"
                                    src="https://placehold.co/100x100/FBBF24/000000?text=PA" alt="Putri Ayu" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        Putri Ayu
                                    </p>
                                    <div class="progress-bar mt-1">
                                        <div class="progress-fill" style="width: 96%"></div>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">96%</span>
                            </li>
                        </ul>
                    </div>

                    <div
                        class="lg:col-span-2 bg-white dark:bg-gray-800 p-5 sm:p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                Absensi Mingguan
                            </h3>
                            <button title="Opsi"
                                class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i data-lucide="more-vertical" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <div class="h-80 relative">
                            <canvas id="weeklyAbsentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <footer
                class="p-4 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700 mt-8">
                &copy; <span id="current-year"></span> Penabur Presensi. Hak Cipta
                Dilindungi.
            </footer>
        </main>

        <nav
            class="md:hidden fixed bottom-0 inset-x-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-lg z-20">
            <div class="flex justify-around items-center h-16 max-w-md mx-auto px-2">
                <a href="#"
                    class="bottom-nav-item active flex flex-col items-center justify-center px-2 py-1 rounded-md w-1/4"
                    aria-current="page">
                    <i data-lucide="layout-dashboard" class="h-5 w-5 mb-1"></i>
                    <span class="text-xs font-medium">Dashboard</span>
                </a>
                <a href="#"
                    class="bottom-nav-item flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 px-2 py-1 rounded-md w-1/4">
                    <i data-lucide="users" class="h-5 w-5 mb-1"></i>
                    <span class="text-xs font-medium">Pengguna</span>
                </a>
                <a href="#"
                    class="bottom-nav-item flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 px-2 py-1 rounded-md w-1/4">
                    <i data-lucide="bar-chart-3" class="h-5 w-5 mb-1"></i>
                    <span class="text-xs font-medium">Laporan</span>
                </a>
                <a href="#"
                    class="bottom-nav-item flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 px-2 py-1 rounded-md w-1/4">
                    <i data-lucide="settings" class="h-5 w-5 mb-1"></i>
                    <span class="text-xs font-medium">Pengaturan</span>
                </a>
            </div>
        </nav>
    </div>

    <script>
        // --- Variabel Global Chart ---
        let totalAttendanceChartInstance = null;
        let genderChartInstance = null;
        let weeklyAbsentChartInstance = null;

        // --- Fungsi Helper Tema & Chart ---
        const getCurrentThemeColors = () => {
            // Periksa tema dari class di <html>, bukan dari localStorage langsung
            const isDark = document.documentElement.classList.contains("dark");
            // Warna disesuaikan untuk tema terang/gelap agar kontras
            return {
                textColor: isDark ? "#d1d5db" : "#374151", // gray-300 : gray-700
                gridColor: isDark ?
                    "rgba(255, 255, 255, 0.1)" :
                    "rgba(0, 0, 0, 0.05)",
                tooltipBg: isDark ? "#1f2937" : "#ffffff", // gray-800 : white
                tooltipText: isDark ? "#f3f4f6" : "#1f2937", // gray-100 : gray-800
                doughnutBorder: isDark ? "#1f2937" : "#ffffff", // gray-800 : white (batas donat)
                radarLine: isDark ?
                    "rgba(167, 139, 250, 0.7)" // purple-400/70
                    :
                    "rgba(79, 70, 229, 0.7)", // indigo-600/70
                radarFill: isDark ?
                    "rgba(167, 139, 250, 0.2)" // purple-400/20
                    :
                    "rgba(79, 70, 229, 0.2)", // indigo-600/20
                barBg: isDark ? "#818cf8" : "#4f46e5", // indigo-400 : indigo-600
                // Definisikan warna lain jika perlu
                colorPrimary: isDark ? "#818cf8" : "#4f46e5", // indigo-400 : indigo-600
                colorPink: isDark ? "#f472b6" : "#ec4899", // pink-400 : pink-500
                colorGray: isDark ? "#6b7280" : "#9ca3af", // gray-500 : gray-400
            };
        };

        const updateAllChartThemes = () => {
            const themeColors = getCurrentThemeColors();
            const updateChart = (chart) => {
                if (!chart) return;

                // Update warna umum
                if (chart.options.plugins?.legend?.labels) {
                    chart.options.plugins.legend.labels.color = themeColors.textColor;
                }
                if (chart.options.plugins?.title?.color) {
                    chart.options.plugins.title.color = themeColors.textColor;
                }
                if (chart.options.plugins?.tooltip) {
                    chart.options.plugins.tooltip.backgroundColor =
                        themeColors.tooltipBg;
                    chart.options.plugins.tooltip.titleColor = themeColors.tooltipText;
                    chart.options.plugins.tooltip.bodyColor = themeColors.tooltipText;
                }

                // Update skala (sumbu)
                if (chart.options.scales) {
                    Object.values(chart.options.scales).forEach((axis) => {
                        if (axis.ticks) {
                            axis.ticks.color = themeColors.textColor;
                        }
                        if (axis.grid) {
                            axis.grid.color = themeColors.gridColor;
                            axis.grid.borderColor = themeColors.gridColor;
                        }
                        if (axis.angleLines) {
                            axis.angleLines.color = themeColors.gridColor;
                        } // Untuk Radar
                        if (axis.pointLabels) {
                            axis.pointLabels.color = themeColors.textColor;
                        } // Untuk Radar
                    });
                }

                // Update warna spesifik per tipe chart
                if (chart.config.type === "doughnut") {
                    chart.config.data.datasets.forEach((dataset) => {
                        dataset.borderColor = themeColors.doughnutBorder;
                        // Update warna background jika perlu (misalnya, jika warna dari themeColors)
                        dataset.backgroundColor = [
                            themeColors.colorPrimary,
                            themeColors.colorPink,
                            themeColors.colorGray,
                        ];
                    });
                }
                if (chart.config.type === "radar") {
                    chart.config.data.datasets.forEach((dataset) => {
                        dataset.borderColor = themeColors.radarLine;
                        dataset.backgroundColor = themeColors.radarFill;
                        dataset.pointBackgroundColor = themeColors.radarLine;
                        dataset.pointBorderColor = themeColors
                        .doughnutBorder; // Use doughnut border for contrast
                        dataset.pointHoverBackgroundColor = themeColors.doughnutBorder;
                        dataset.pointHoverBorderColor = themeColors.radarLine;
                    });
                }
                if (chart.config.type === "bar") {
                    chart.config.data.datasets.forEach((dataset) => {
                        dataset.backgroundColor = themeColors.barBg;
                    });
                }

                chart.update("none"); // Update tanpa animasi
            };
            updateChart(totalAttendanceChartInstance);
            updateChart(genderChartInstance);
            updateChart(weeklyAbsentChartInstance);
        };

        // --- Inisialisasi Saat DOM Siap ---
        document.addEventListener("DOMContentLoaded", () => {
            // --- Ganti Ikon dengan Lucide ---
            if (typeof lucide !== "undefined") {
                lucide.createIcons();
            }

            // --- Referensi Elemen ---
            const currentYearSpan = document.getElementById("current-year");
            const bottomNavItems = document.querySelectorAll(".bottom-nav-item");
            const topNavItems = document.querySelectorAll(".top-nav-item");

            // --- Footer Year ---
            if (currentYearSpan) {
                currentYearSpan.textContent = new Date().getFullYear();
            }

            // --- Status Aktif Navigasi (Sederhana) ---
            // Fungsi untuk menangani klik navigasi (DRY)
            const handleNavClick = (items, activeClasses, inactiveClasses) => {
                items.forEach((item) => {
                    item.addEventListener("click", (e) => {
                        // Hapus 'active' dari semua
                        items.forEach((el) => {
                            el.classList.remove(...activeClasses);
                            el.classList.add(...
                            inactiveClasses); // Tambahkan kembali class non-aktif jika ada
                            el.removeAttribute("aria-current");
                        });
                        // Tambahkan 'active' ke yang diklik
                        item.classList.add(...activeClasses);
                        item.classList.remove(...inactiveClasses); // Hapus class non-aktif
                        item.setAttribute("aria-current", "page");
                    });
                });
            };

            // Terapkan untuk Top Nav
            handleNavClick(
                topNavItems,
                [
                    "active",
                    "bg-indigo-50",
                    "dark:bg-indigo-800",
                    "text-indigo-600",
                    "dark:text-indigo-200",
                    "font-semibold",
                ],
                ["text-gray-500", "dark:text-gray-300"] // Kelas non-aktif dasar
            );

            // Terapkan untuk Bottom Nav
            handleNavClick(
                bottomNavItems,
                ["active", "text-indigo-600", "dark:text-indigo-400"],
                ["text-gray-500", "dark:text-gray-400"] // Kelas non-aktif dasar
            );

            // --- Inisialisasi Chart.js ---
            const initCharts = () => {
                const themeColors = getCurrentThemeColors(); // Dapatkan warna tema saat ini

                // Hancurkan chart lama jika ada sebelum membuat yang baru
                if (totalAttendanceChartInstance)
                    totalAttendanceChartInstance.destroy();
                if (genderChartInstance) genderChartInstance.destroy();
                if (weeklyAbsentChartInstance) weeklyAbsentChartInstance.destroy();

                // 1. Total Attendance Report (Bar Chart)
                const totalAttendanceCtx = document
                    .getElementById("totalAttendanceChart")
                    ?.getContext("2d");
                if (totalAttendanceCtx) {
                    totalAttendanceChartInstance = new Chart(totalAttendanceCtx, {
                        type: "bar",
                        data: {
                            labels: [
                                "Jan",
                                "Feb",
                                "Mar",
                                "Apr",
                                "Mei",
                                "Jun",
                                "Jul",
                                "Agu",
                                "Sep",
                                "Okt",
                                "Nov",
                                "Des",
                            ],
                            datasets: [{
                                label: "Total Kehadiran",
                                data: [
                                    570, 630, 400, 500, 450, 600, 700, 650, 580, 620, 680,
                                    710,
                                ],
                                backgroundColor: themeColors.barBg, // Warna dari tema
                                borderRadius: 4,
                                barPercentage: 0.6,
                                categoryPercentage: 0.7,
                            }, ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: themeColors.gridColor,
                                        borderColor: themeColors.gridColor,
                                    },
                                    ticks: {
                                        color: themeColors.textColor
                                    },
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: themeColors.textColor
                                    },
                                },
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: themeColors.tooltipBg,
                                    titleColor: themeColors.tooltipText,
                                    bodyColor: themeColors.tooltipText,
                                    padding: 10,
                                    boxPadding: 4,
                                    cornerRadius: 4,
                                },
                            },
                        },
                    });
                }

                // 2. Students by Gender (Doughnut Chart)
                const genderCtx = document
                    .getElementById("genderChart")
                    ?.getContext("2d");
                if (genderCtx) {
                    genderChartInstance = new Chart(genderCtx, {
                        type: "doughnut",
                        data: {
                            labels: ["Laki-laki", "Perempuan", "Lainnya"],
                            datasets: [{
                                label: "Gender Siswa",
                                data: [410, 150, 10], // Data dari gambar
                                backgroundColor: [
                                    themeColors.colorPrimary,
                                    themeColors.colorPink,
                                    themeColors.colorGray,
                                ],
                                borderColor: themeColors.doughnutBorder,
                                borderWidth: 3,
                                hoverOffset: 8,
                            }, ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: "70%", // Buat lubang lebih besar
                            plugins: {
                                legend: {
                                    position: "bottom",
                                    labels: {
                                        color: themeColors.textColor,
                                        boxWidth: 12,
                                        padding: 15,
                                    },
                                },
                                tooltip: {
                                    backgroundColor: themeColors.tooltipBg,
                                    titleColor: themeColors.tooltipText,
                                    bodyColor: themeColors.tooltipText,
                                    padding: 10,
                                    boxPadding: 4,
                                    cornerRadius: 4,
                                },
                            },
                        },
                    });
                }

                // 3. Weekly Absent (Radar Chart)
                const weeklyAbsentCtx = document
                    .getElementById("weeklyAbsentChart")
                    ?.getContext("2d");
                if (weeklyAbsentCtx) {
                    weeklyAbsentChartInstance = new Chart(weeklyAbsentCtx, {
                        type: "radar",
                        data: {
                            labels: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"],
                            datasets: [{
                                label: "Jumlah Absen",
                                data: [15, 10, 20, 12, 18, 5, 8],
                                borderColor: themeColors.radarLine,
                                backgroundColor: themeColors.radarFill,
                                pointBackgroundColor: themeColors.radarLine,
                                pointBorderColor: themeColors.doughnutBorder,
                                pointHoverBackgroundColor: themeColors.doughnutBorder,
                                pointHoverBorderColor: themeColors.radarLine,
                                borderWidth: 1.5,
                                pointRadius: 3,
                                pointHoverRadius: 5,
                            }, ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                r: {
                                    angleLines: {
                                        color: themeColors.gridColor
                                    },
                                    grid: {
                                        color: themeColors.gridColor
                                    },
                                    pointLabels: {
                                        color: themeColors.textColor,
                                        font: {
                                            size: 11
                                        },
                                    },
                                    ticks: {
                                        display: false
                                    }, // Sembunyikan angka pada sumbu r
                                    suggestedMin: 0,
                                },
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: themeColors.tooltipBg,
                                    titleColor: themeColors.tooltipText,
                                    bodyColor: themeColors.tooltipText,
                                    padding: 10,
                                    boxPadding: 4,
                                    cornerRadius: 4,
                                },
                            },
                        },
                    });
                }
            }; // End initCharts

            initCharts(); // Panggil inisialisasi chart awal
            // Tidak perlu memanggil updateAllChartThemes di sini karena sudah dihandle oleh AlpineJS init()

            // Tambahkan listener untuk resize atau perubahan orientasi jika perlu
            // window.addEventListener('resize', () => {
            //     // Mungkin perlu re-init atau update chart jika ukuran berubah drastis
            // });
        }); // End DOMContentLoaded
    </script>
</body>

</html>
