@extends('layouts.dashboard')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Data Siswa</title>
        <!-- Tailwind CSS via CDN -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            /* Tambahan style jika diperlukan */
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
            <button onclick="showModal('addSiswaModal')"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mb-4">
                Tambah Siswa
            </button>
            <!-- Tabel Data Siswa -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow">
                    <thead class="bg-gray-200 dark:bg-gray-700 border-b">
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">NISN</th>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Email</th>
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
        <div id="addSiswaModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-96 max-w-md p-6">
                <div class="flex justify-between items-center border-b pb-2">
                    <h5 class="text-lg font-bold">Tambah Siswa</h5>
                    <button onclick="hideModal('addSiswaModal')" class="text-gray-500 hover:text-gray-700">✖</button>
                </div>
                <form id="addSiswaForm" class="py-4">
                    <div class="mb-3">
                        <label for="addNISN" class="block font-semibold">NISN</label>
                        <input type="text" id="addNISN" name="nisn" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label for="addName" class="block font-semibold">Nama</label>
                        <input type="text" id="addName" name="name" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label for="addEmail" class="block font-semibold">Email</label>
                        <input type="email" id="addEmail" name="email" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label for="addPassword" class="block font-semibold">Password</label>
                        <input type="password" id="addPassword" name="password" class="w-full border rounded px-3 py-2">
                    </div>
                    <!-- Dropdown untuk memilih Kelas -->
                    <div class="mb-3">
                        <label for="addKelas" class="block font-semibold">Kelas</label>
                        <select id="addKelas" name="kelas" class="w-full border rounded px-3 py-2"></select>
                    </div>
                    <!-- Dropdown untuk memilih Jurusan -->
                    <div class="mb-3">
                        <label for="addJurusan" class="block font-semibold">Jurusan</label>
                        <select id="addJurusan" name="jurusan" class="w-full border rounded px-3 py-2"></select>
                    </div>
                    <!-- Dropdown untuk memilih Tahun Ajaran -->
                    <div class="mb-3">
                        <label for="addTahunAjaran" class="block font-semibold">Tahun Ajaran</label>
                        <select id="addTahunAjaran" name="tahun_ajaran" class="w-full border rounded px-3 py-2"></select>
                    </div>
                </form>
                <div class="flex justify-end border-t pt-2">
                    <button onclick="hideModal('addSiswaModal')"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">Batal</button>
                    <button id="addSiswaButton"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </div>
        </div>

        <!-- MODAL: Edit Siswa -->
        <div id="editSiswaModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-96 max-w-md mx-auto">
                <div class="flex justify-between items-center border-b p-4">
                    <h5 class="text-lg font-bold">Edit Siswa</h5>
                    <button onclick="hideModal('editSiswaModal')" class="text-gray-500 hover:text-gray-700">✖</button>
                </div>
                <form id="editSiswaForm" class="p-4">
                    <input type="hidden" id="editId" name="id">
                    <div class="mb-3">
                        <label for="editNISN" class="block font-semibold">NISN</label>
                        <input type="text" id="editNISN" name="nisn" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label for="editName" class="block font-semibold">Nama</label>
                        <input type="text" id="editName" name="name" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="block font-semibold">Email</label>
                        <input type="email" id="editEmail" name="email" class="w-full border rounded px-3 py-2">
                    </div>
                    <!-- Dropdown untuk memilih Kelas -->
                    <div class="mb-3">
                        <label for="editKelas" class="block font-semibold">Kelas</label>
                        <select id="editKelas" name="kelas" class="w-full border rounded px-3 py-2"></select>
                    </div>
                    <!-- Dropdown untuk memilih Jurusan -->
                    <div class="mb-3">
                        <label for="editJurusan" class="block font-semibold">Jurusan</label>
                        <select id="editJurusan" name="jurusan" class="w-full border rounded px-3 py-2"></select>
                    </div>
                    <!-- Dropdown untuk memilih Tahun Ajaran -->
                    <div class="mb-3">
                        <label for="editTahunAjaran" class="block font-semibold">Tahun Ajaran</label>
                        <select id="editTahunAjaran" name="tahun_ajaran"
                            class="w-full border rounded px-3 py-2"></select>
                    </div>
                </form>
                <div class="flex justify-end border-t p-4">
                    <button onclick="hideModal('editSiswaModal')"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">Batal</button>
                    <button id="editSiswaButton"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </div>
        </div>

        <!-- MODAL: Hapus Siswa -->
        <div id="deleteSiswaModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-96 max-w-md mx-auto">
                <div class="flex justify-between items-center border-b p-4">
                    <h5 class="text-lg font-bold">Hapus Siswa</h5>
                    <button onclick="hideModal('deleteSiswaModal')" class="text-gray-500 hover:text-gray-700">✖</button>
                </div>
                <div class="p-4">
                    <p>Apakah Anda yakin ingin menghapus siswa ini?</p>
                </div>
                <div class="flex justify-end border-t p-4">
                    <button onclick="hideModal('deleteSiswaModal')"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">Batal</button>
                    <button id="deleteSiswaButton"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Hapus</button>
                </div>
            </div>
        </div>

        <!-- NOTIFICATION -->
        <div id="notification" class="hidden fixed top-5 right-5 p-4 bg-blue-600 text-white rounded shadow">
            <span id="notificationMessage"></span>
        </div>

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
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }
            }
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
                // Load opsi dropdown untuk kelas, jurusan, tahun ajaran
                fetchKelasOptions();
                // Load data siswa awal
                loadData();

                function fetchKelasOptions() {
                    $.ajax({
                        url: '{{ route('siswa.create') }}',
                        type: 'GET',
                        success: function(response) {
                            var kelasOptions = response.kelas;
                            var jurusanOptions = response.jurusan;
                            var tahunAjaranOptions = response.tahun_ajaran;

                            var addKelasSelect = $('#addKelas');
                            var addJurusanSelect = $('#addJurusan');
                            var addTahunAjaranSelect = $('#addTahunAjaran');
                            addKelasSelect.empty();
                            addJurusanSelect.empty();
                            addTahunAjaranSelect.empty();
                            kelasOptions.forEach(function(opt) {
                                addKelasSelect.append('<option value="' + opt.kelas + '">' + opt
                                    .kelas + '</option>');
                            });
                            jurusanOptions.forEach(function(opt) {
                                addJurusanSelect.append('<option value="' + opt.jurusan + '">' + opt
                                    .jurusan + '</option>');
                            });
                            tahunAjaranOptions.forEach(function(opt) {
                                addTahunAjaranSelect.append('<option value="' + opt.tahun_ajaran +
                                    '">' + opt.tahun_ajaran + '</option>');
                            });

                            // Untuk modal edit, populate dropdown serupa
                            var editKelasSelect = $('#editKelas');
                            var editJurusanSelect = $('#editJurusan');
                            var editTahunAjaranSelect = $('#editTahunAjaran');
                            editKelasSelect.empty();
                            editJurusanSelect.empty();
                            editTahunAjaranSelect.empty();
                            kelasOptions.forEach(function(opt) {
                                editKelasSelect.append('<option value="' + opt.kelas + '">' + opt
                                    .kelas + '</option>');
                            });
                            jurusanOptions.forEach(function(opt) {
                                editJurusanSelect.append('<option value="' + opt.jurusan + '">' +
                                    opt.jurusan + '</option>');
                            });
                            tahunAjaranOptions.forEach(function(opt) {
                                editTahunAjaranSelect.append('<option value="' + opt.tahun_ajaran +
                                    '">' + opt.tahun_ajaran + '</option>');
                            });
                        },
                        error: function(xhr) {
                            alert(xhr.responseText);
                        }
                    });
                }

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
                                html += '<tr class="border-b">';
                                html += '<td class="px-4 py-2">' + s.id + '</td>';
                                html += '<td class="px-4 py-2">' + s.nisn + '</td>';
                                html += '<td class="px-4 py-2">' + s.name + '</td>';
                                html += '<td class="px-4 py-2">' + s.email + '</td>';
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
                            alert('Error loading data.');
                        }
                    });
                }

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
                            hideModal('addSiswaModal');
                            $('#addSiswaForm')[0].reset();
                            loadData();
                            showNotification(response.message, 'success');
                        },
                        error: function(xhr) {
                            alert(xhr.responseText);
                        }
                    });
                });

                $(document).on('click', '.editSiswa', function() {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '/siswa/' + id + '/edit',
                        type: 'GET',
                        success: function(response) {
                            $('#editId').val(response.siswa.id);
                            $('#editNISN').val(response.siswa.nisn);
                            $('#editName').val(response.siswa.name);
                            $('#editEmail').val(response.siswa.email);
                            if (response.siswa.kelas) {
                                $('#editKelas').val(response.siswa.kelas.kelas);
                                $('#editJurusan').val(response.siswa.kelas.jurusan);
                                $('#editTahunAjaran').val(response.siswa.kelas.tahun_ajaran);
                            } else {
                                $('#editKelas').val('');
                                $('#editJurusan').val('');
                                $('#editTahunAjaran').val('');
                            }
                            showModal('editSiswaModal');
                        },
                        error: function(xhr) {
                            console.error('Error fetching data:', xhr.responseText);
                            alert('Error fetching data.');
                        }
                    });
                });

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
                            alert('Error updating siswa.');
                        }
                    });
                });

                $(document).on('click', '.deleteSiswa', function() {
                    var id = $(this).data('id');
                    $('#deleteId').val(id);
                    showModal('deleteSiswaModal');
                });

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
                            alert('Error deleting siswa.');
                        }
                    });
                });

                function showNotification(message, type = 'success') {
                    var notification = $('#notification');
                    $('#notificationMessage').text(message);
                    notification.removeClass('hidden bg-blue-600 bg-red-600');
                    notification.addClass(type === 'success' ? 'bg-blue-600' : 'bg-red-600');
                    setTimeout(function() {
                        notification.addClass('hidden');
                    }, 3000);
                }
            });
        </script>
    </body>

    </html>
@endsection
