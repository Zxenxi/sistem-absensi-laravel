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
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100">
        <div class="container mx-auto py-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold mb-4">Data Siswa</h1>
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
        <div id="addSiswaModal" class="z-20 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden ">
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
                    <!-- Single dropdown untuk Kelas -->
                    <div class="mb-3">
                        <label for="addKelas" class="block font-semibold">Kelas</label>
                        <select id="addKelas" name="kelas_id" class="w-full border rounded px-3 py-2"></select>
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
                    <!-- Single dropdown untuk Kelas -->
                    <div class="mb-3">
                        <label for="editKelas" class="block font-semibold">Kelas</label>
                        <select id="editKelas" name="kelas_id" class="w-full border rounded px-3 py-2"></select>
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

        <!-- Script AJAX dan fungsi CRUD -->
        <script>
            $(document).ready(function() {
                // Load opsi dropdown untuk kelas
                fetchKelasOptions();
                // Load data siswa awal
                loadData();

                function fetchKelasOptions() {
                    $.ajax({
                        url: '{{ route('siswa.create') }}',
                        type: 'GET',
                        success: function(response) {
                            var addKelasSelect = $('#addKelas');
                            var editKelasSelect = $('#editKelas');
                            addKelasSelect.empty();
                            editKelasSelect.empty();

                            // Populate dropdown dengan opsi dari kelas_options
                            response.kelas_options.forEach(function(kelas) {
                                var optionText = kelas.kelas + " - " + kelas.jurusan + " - " + kelas
                                    .tahun_ajaran;
                                addKelasSelect.append('<option value="' + kelas.id + '">' +
                                    optionText + '</option>');
                                editKelasSelect.append('<option value="' + kelas.id + '">' +
                                    optionText + '</option>');
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseText, 'error');
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
                            siswa.forEach(function(s, index) {
                                html += '<tr class="border-b">';
                                html += '<td class="px-4 py-2">' + (index + 1) + '</td>';
                                html += '<td class="px-4 py-2">' + s.nisn + '</td>';
                                html += '<td class="px-4 py-2">' + s.name + '</td>';
                                html += '<td class="px-4 py-2">' + s.email + '</td>';
                                html += '<td class="px-4 py-2">' + (s.kelas ? (s.kelas.kelas +
                                    " - " + s.kelas.jurusan + " - " + s.kelas.tahun_ajaran
                                ) : 'N/A') + '</td>';
                                html += '<td class="px-4 py-2">';
                                html +=
                                    '<button class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded mr-2 editSiswa" data-id="' +
                                    s.id + '">Edit</button>';
                                html +=
                                    '<button class="bg-red-600 hover:bg-red-600 text-white px-2 py-1 rounded deleteSiswa" data-id="' +
                                    s.id + '">Hapus</button>';
                                html += '</td></tr>';
                            });
                            $('#siswaTableBody').html(html);
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Error loading data.', 'error');
                        }
                    });
                }

                // Tambah Siswa
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseText, 'error');
                        }
                    });
                });

                // Buka modal Edit Siswa dan populate data
                $(document).on('click', '.editSiswa', function() {
                    var id = $(this).data('id');
                    $.ajax({
                        url: '/siswa/' + id + '/edit',
                        type: 'GET',
                        success: function(response) {
                            // Pastikan dropdown selalu diperbarui
                            fetchKelasOptions();
                            $('#editId').val(response.siswa.id);
                            $('#editNISN').val(response.siswa.nisn);
                            $('#editName').val(response.siswa.name);
                            $('#editEmail').val(response.siswa.email);
                            if (response.siswa.kelas) {
                                $('#editKelas').val(response.siswa.kelas.id);
                            } else {
                                $('#editKelas').val('');
                            }
                            showModal('editSiswaModal');
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Error fetching data.', 'error');
                        }
                    });
                });

                // Update Siswa
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            Swal.fire('Error', xhr.responseText, 'error');
                        }
                    });
                });

                // Hapus Siswa
                $(document).on('click', '.deleteSiswa', function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah Anda yakin ingin menghapus siswa ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/siswa/' + id,
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    loadData();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire('Error', 'Error deleting siswa.', 'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    </body>

    </html>
@endsection
