@extends('layouts.dashboard')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Siswa (Tailwind Version)</title>
        <!-- Tailwind CSS via CDN -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            /* Jika perlu, tambahkan style tambahan di sini */
        </style>
    </head>

    <body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100">
        <div class="container mx-auto py-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold mb-4">Data Siswa</h1>
                <!-- Tombol Toggle Dark Mode -->
                <button onclick="toggleDarkMode()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 px-4 py-2 rounded">
                    Toggle Dark Mode
                </button>
            </div>
            <!-- Tombol Tambah Siswa -->
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mb-4 transition-all"
                onclick="showModal('addSiswaModal')">
                Tambah Siswa
            </button>

            <!-- Tabel Data Siswa -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow">
                    <thead class="bg-gray-200 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600">
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">NISN</th>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Kelas</th>
                            <th class="px-4 py-2 text-left">Jurusan</th>
                            <th class="px-4 py-2 text-left">Tahun Ajaran</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="siswaTableBody">
                        <!-- Data siswa akan di-load via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL: Tambah Siswa -->
        <div id="addSiswaModal" class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-60 max-w-md mx-auto p-6">
                <div class="flex justify-between items-center border-b pb-2 border-gray-300 dark:border-gray-600">
                    <h5 class="text-lg font-bold">Tambah Siswa</h5>
                    <button class="text-gray-500 hover:text-gray-700" onclick="hideModal('addSiswaModal')">✖</button>
                </div>
                <div class="py-4">
                    <form id="addSiswaForm">
                        <div class="mb-3">
                            <label for="addNISN" class="block font-semibold mb-1">NISN</label>
                            <input type="text" id="addNISN" name="nisn"
                                class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div class="mb-3">
                            <label for="addNama" class="block font-semibold mb-1">Nama</label>
                            <input type="text" id="addNama" name="nama"
                                class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-100">
                        </div>
                        <div class="mb-3">
                            <label for="addKelasId" class="block font-semibold mb-1">Kelas</label>
                            <select id="addKelasId" name="kelas_id"
                                class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-100">
                                <!-- Data kelas akan di-load via AJAX -->
                            </select>
                        </div>
                    </form>
                </div>
                <div class="flex justify-end border-t pt-2 border-gray-300 dark:border-gray-600">
                    <button
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-100 px-4 py-2 rounded mr-2"
                        onclick="hideModal('addSiswaModal')">Batal</button>
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                        id="addSiswaButton">Simpan</button>
                </div>
            </div>
        </div>

        <!-- MODAL: Edit Siswa -->
        <div id="editSiswaModal" class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50 hidden"
            aria-labelledby="editSiswaModalLabel" aria-hidden="true">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-60 max-w-md mx-auto">
                <div class="flex justify-between items-center border-b p-4 border-gray-300 dark:border-gray-600">
                    <h5 class="text-lg font-bold" id="editSiswaModalLabel">Edit Siswa</h5>
                    <button class="text-gray-500 hover:text-gray-700" onclick="hideModal('editSiswaModal')">&times;</button>
                </div>
                <div class="p-4">
                    <form id="editSiswaForm">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editNISN" class="block font-semibold mb-1">NISN</label>
                            <input type="text"
                                class="w-full border border-gray-300 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                id="editNISN" name="nisn">
                        </div>
                        <div class="mb-3">
                            <label for="editNama" class="block font-semibold mb-1">Nama</label>
                            <input type="text"
                                class="w-full border border-gray-300 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                id="editNama" name="nama">
                        </div>
                        <div class="mb-3">
                            <label for="editKelasId" class="block font-semibold mb-1">Kelas</label>
                            <select
                                class="w-full border border-gray-300 dark:bg-gray-700 dark:text-gray-100 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                id="editKelasId" name="kelas_id">
                                <!-- Data kelas akan di-load via AJAX -->
                            </select>
                        </div>
                    </form>
                </div>
                <div class="flex justify-end border-t p-4 border-gray-300 dark:border-gray-600">
                    <button
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-100 px-4 py-2 rounded mr-2"
                        onclick="hideModal('editSiswaModal')">Close</button>
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                        id="editSiswaButton">Save</button>
                </div>
            </div>
        </div>

        <!-- MODAL: Hapus Siswa -->
        <div id="deleteSiswaModal"
            class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50 hidden"
            aria-labelledby="deleteSiswaModalLabel" aria-hidden="true">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-60 max-w-md mx-auto">
                <div class="flex justify-between items-center border-b p-4 border-gray-300 dark:border-gray-600">
                    <h5 class="text-lg font-bold" id="deleteSiswaModalLabel">Hapus Siswa</h5>
                    <button class="text-gray-500 hover:text-gray-700"
                        onclick="hideModal('deleteSiswaModal')">&times;</button>
                </div>
                <div class="p-4">
                    <p>Apakah Anda yakin ingin menghapus siswa ini?</p>
                </div>
                <div class="flex justify-end border-t p-4 border-gray-300 dark:border-gray-600">
                    <button
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-100 px-4 py-2 rounded mr-2"
                        onclick="hideModal('deleteSiswaModal')">Close</button>
                    <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                        id="deleteSiswaButton">Delete</button>
                </div>
            </div>
        </div>

        <!-- NOTIFICATION -->
        <div id="notification" class="hidden fixed top-5 right-5 p-4 bg-blue-600 text-white rounded shadow">
            <span id="notificationMessage"></span>
        </div>

        <!-- Input tersembunyi untuk menyimpan ID delete -->
        <input type="hidden" id="deleteId">

        <!-- Fungsi Modal Global -->
        <script>
            function showModal(id) {
                $('#' + id).removeClass('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function hideModal(id) {
                $('#' + id).addClass('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        </script>

        <!-- Dark Mode Toggle Script -->
        <script>
            function toggleDarkMode() {
                document.documentElement.classList.toggle('dark');
                // Simpan preferensi di localStorage
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }
            }
            // Set dark mode berdasarkan preferensi pengguna atau sistem saat halaman dimuat
            (function() {
                if (localStorage.getItem('theme') === 'dark' ||
                    (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>

        <!-- Script AJAX dan fungsi CRUD -->
        <script>
            $(document).ready(function() {
                // Load data kelas untuk form tambah & edit
                fetchKelasOptions();
                // Load data siswa awal
                loadData();

                // ---------------------------
                // FUNGSI: Tambah Siswa
                // ---------------------------
                $('#addSiswaButton').click(function() {
                    var formData = $('#addSiswaForm').serialize();
                    $.ajax({
                        url: '{{ route('siswa.store') }}',
                        type: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            hideModal('addSiswaModal'); // Sembunyikan modal setelah berhasil tambah
                            $('#addSiswaForm')[0].reset();
                            loadData();
                            showNotification('Siswa berhasil ditambahkan', 'success');
                        },
                        error: function(xhr) {
                            alert(xhr.responseText);
                        }
                    });
                });

                // ---------------------------
                // FUNGSI: Load Kelas Options
                // ---------------------------
                function fetchKelasOptions() {
                    $.ajax({
                        url: '{{ route('siswa.create') }}',
                        type: 'GET',
                        success: function(response) {
                            var kelas = response.kelas;
                            $('#addKelasId').empty();
                            $('#editKelasId').empty();
                            kelas.forEach(function(k) {
                                var option = '<option value="' + k.id + '">' + k.kelas + ' - ' + k
                                    .jurusan + ' - ' + k.tahun_ajaran + '</option>';
                                $('#addKelasId').append(option);
                                $('#editKelasId').append(option);
                            });
                        },
                        error: function(xhr) {
                            alert(xhr.responseText);
                        }
                    });
                }

                // ---------------------------
                // FUNGSI: Load Data Siswa
                // ---------------------------
                function loadData() {
                    $.ajax({
                        url: '{{ route('siswa.index') }}',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            var siswa = response.siswa;
                            var html = '';
                            siswa.forEach(function(s) {
                                html +=
                                    '<tr class="border-b border-gray-200 dark:border-gray-700">';
                                html += '<td class="px-4 py-2">' + s.id + '</td>';
                                html += '<td class="px-4 py-2">' + s.nisn + '</td>';
                                html += '<td class="px-4 py-2">' + s.nama + '</td>';
                                html += '<td class="px-4 py-2">' + (s.kelas ? s.kelas.kelas :
                                    'N/A') + '</td>';
                                html += '<td class="px-4 py-2">' + (s.kelas ? s.kelas.jurusan :
                                    'N/A') + '</td>';
                                html += '<td class="px-4 py-2">' + (s.kelas ? s.kelas.tahun_ajaran :
                                    'N/A') + '</td>';
                                html += '<td class="px-4 py-2">';
                                html +=
                                    '<button class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded mr-2 editSiswa" data-id="' +
                                    s.id + '">Edit</button>';
                                html +=
                                    '<button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded deleteSiswa" data-id="' +
                                    s.id + '">Hapus</button>';
                                html += '</td>';
                                html += '</tr>';
                            });
                            $('#siswaTableBody').html(html);
                        },
                        error: function(xhr) {
                            console.error('Error loading data:', xhr.responseText);
                            alert('Error loading data. Silakan cek console untuk detail.');
                        }
                    });
                }

                // ---------------------------
                // FUNGSI: Edit Siswa (Ambil data & tampilkan modal)
                // ---------------------------
                $(document).on('click', '.editSiswa', function() {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '/siswa/' + id + '/edit',
                        type: 'GET',
                        success: function(response) {
                            $('#editId').val(response.siswa.id);
                            $('#editNISN').val(response.siswa.nisn);
                            $('#editNama').val(response.siswa.nama);
                            $('#editKelasId').val(response.siswa.kelas_id);
                            showModal('editSiswaModal');
                        },
                        error: function(xhr) {
                            console.error('Error fetching data:', xhr.responseText);
                            alert('Error fetching data. Lihat console untuk detail.');
                        }
                    });
                });

                // ---------------------------
                // FUNGSI: Update Siswa
                // ---------------------------
                $('#editSiswaButton').click(function() {
                    var id = $('#editId').val();
                    var formData = $('#editSiswaForm').serialize();
                    $.ajax({
                        url: '/siswa/' + id,
                        type: 'PUT',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            hideModal('editSiswaModal');
                            loadData();
                            showNotification(response.message, 'success');
                        },
                        error: function(xhr) {
                            console.error('Error updating siswa:', xhr.responseText);
                            alert('Error updating siswa. Silakan cek console.');
                        }
                    });
                });

                // ---------------------------
                // FUNGSI: Konfirmasi Hapus Siswa
                // ---------------------------
                $(document).on('click', '.deleteSiswa', function() {
                    var id = $(this).data('id');
                    $('#deleteId').val(id);
                    showModal('deleteSiswaModal');
                });

                // ---------------------------
                // FUNGSI: Hapus Siswa
                // ---------------------------
                $('#deleteSiswaButton').click(function() {
                    var id = $('#deleteId').val();
                    $.ajax({
                        url: '/siswa/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            hideModal('deleteSiswaModal');
                            loadData();
                            showNotification(response.message, 'success');
                        },
                        error: function(xhr) {
                            console.error('Error deleting siswa:', xhr.responseText);
                            alert('Error deleting siswa. Silakan cek console.');
                        }
                    });
                });

                // ---------------------------
                // FUNGSI: Notification
                // ---------------------------
                function showNotification(message, type = 'success') {
                    const notification = document.getElementById('notification');
                    const messageSpan = document.getElementById('notificationMessage');
                    messageSpan.textContent = message;
                    notification.classList.remove('hidden', 'bg-blue-600', 'bg-red-600');
                    notification.classList.add(type === 'success' ? 'bg-blue-600' : 'bg-red-600');
                    setTimeout(() => {
                        notification.classList.add('hidden');
                    }, 3000);
                }
            });
        </script>
    </body>

    </html>
@endsection
