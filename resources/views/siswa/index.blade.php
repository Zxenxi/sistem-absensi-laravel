@extends('layouts.dashboard')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container mx-auto py-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4 sm:mb-0">Data Siswa</h1>
            <button onclick="showModal('addSiswaModal')"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md w-full sm:w-auto">
                Tambah Siswa
            </button>
        </div>

        <!-- Tabel Data Siswa -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
            <table id="siswaTable" class="min-w-full">
                <thead class="bg-gray-100 dark:bg-gray-700 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">ID</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">NISN</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Nama</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Email</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Kelas</th>
                        <th class="px-4 py-2 text-center text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300">
                    <!-- Data siswa dimuat secara dinamis via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Tambah Siswa -->
    <div id="addSiswaModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-10">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
            <div class="flex justify-between items-center border-b pb-2">
                <h5 class="text-lg font-bold text-gray-800 dark:text-gray-100">Tambah Siswa</h5>
                <button onclick="hideModal('addSiswaModal')" class="text-gray-500 hover:text-gray-700">✖</button>
            </div>
            <form id="addSiswaForm" class="py-4">
                @csrf
                <div class="mb-3">
                    <label for="addNISN" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">NISN</label>
                    <input type="text" id="addNISN" name="nisn" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label for="addName" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama</label>
                    <input type="text" id="addName" name="name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label for="addEmail" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" id="addEmail" name="email" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label for="addPassword"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Password</label>
                    <input type="password" id="addPassword" name="password" class="w-full border rounded px-3 py-2"
                        required>
                </div>
                <!-- Dropdown Kelas -->
                <div class="mb-3">
                    <label for="addKelas" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Kelas</label>
                    <select id="addKelas" name="kelas_id" class="w-full border rounded px-3 py-2" required></select>
                </div>
            </form>
            <div class="flex justify-end border-t pt-2">
                <button onclick="hideModal('addSiswaModal')"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">
                    Batal
                </button>
                <button id="addSiswaButton" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Siswa -->
    <div id="editSiswaModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-10">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
            <div class="flex justify-between items-center border-b pb-2">
                <h5 class="text-lg font-bold text-gray-800 dark:text-gray-100">Edit Siswa</h5>
                <button onclick="hideModal('editSiswaModal')" class="text-gray-500 hover:text-gray-700">✖</button>
            </div>
            <form id="editSiswaForm" class="py-4">
                @csrf
                <input type="hidden" id="editId" name="id">
                <div class="mb-3">
                    <label for="editNISN" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">NISN</label>
                    <input type="text" id="editNISN" name="nisn" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label for="editName" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Nama</label>
                    <input type="text" id="editName" name="name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label for="editEmail"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" id="editEmail" name="email" class="w-full border rounded px-3 py-2" required>
                </div>
                <!-- Field Password untuk Update (opsional) -->
                <div class="mb-3">
                    <label for="editPassword" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Password Baru
                        <span class="text-xs italic text-gray-500">(Kosongkan jika tidak ingin mengganti)</span>
                    </label>
                    <input type="password" id="editPassword" name="password" class="w-full border rounded px-3 py-2"
                        placeholder="Masukkan password baru jika ingin mengganti">
                </div>
                <!-- Dropdown Kelas -->
                <div class="mb-3">
                    <label for="editKelas"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Kelas</label>
                    <select id="editKelas" name="kelas_id" class="w-full border rounded px-3 py-2" required></select>
                </div>
            </form>
            <div class="flex justify-end border-t pt-2">
                <button onclick="hideModal('editSiswaModal')"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">
                    Batal
                </button>
                <button id="editSiswaButton" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Simpan
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery, DataTables, SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Setup global AJAX untuk CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Modal control functions
        function showModal(id) {
            $('#' + id).removeClass('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function hideModal(id) {
            $('#' + id).addClass('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        $(document).ready(function() {
            // Inisialisasi DataTable untuk siswa
            var siswaTable = $('#siswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('siswa.index') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nisn',
                        name: 'nisn'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'kelas',
                        name: 'kelas'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    searchPlaceholder: "Cari data siswa...",
                    search: ""
                },
                responsive: true,
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });

            // Fetch dropdown options untuk kelas pada modal tambah & edit
            function fetchKelasOptions() {
                $.ajax({
                    url: '{{ route('siswa.create') }}',
                    type: 'GET',
                    success: function(response) {
                        var addKelasSelect = $('#addKelas');
                        var editKelasSelect = $('#editKelas');
                        addKelasSelect.empty();
                        editKelasSelect.empty();
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
                        Swal.fire('Error', 'Gagal memuat opsi kelas.', 'error');
                    }
                });
            }
            fetchKelasOptions();

            // Proses tambah siswa
            $('#addSiswaButton').click(function() {
                var formData = $('#addSiswaForm').serialize();
                $.ajax({
                    url: '{{ route('siswa.store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            hideModal('addSiswaModal');
                            $('#addSiswaForm')[0].reset();
                            siswaTable.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseText, 'error');
                    }
                });
            });

            // Buka modal edit siswa dan ambil data untuk diisi
            $(document).on('click', '.editSiswa', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/siswa/' + id + '/edit',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#editId').val(response.siswa.id);
                            $('#editNISN').val(response.siswa.nisn);
                            $('#editName').val(response.siswa.name);
                            $('#editEmail').val(response.siswa.email);
                            // Pastikan dropdown kelas di-set sesuai data siswa jika ada
                            if (response.siswa.kelas) {
                                $('#editKelas').val(response.siswa.kelas.id);
                            } else {
                                $('#editKelas').val('');
                            }
                            // Kosongkan field password agar user bisa mengisi jika ingin mengganti
                            $('#editPassword').val('');
                            showModal('editSiswaModal');
                        } else {
                            Swal.fire('Error', 'Gagal memuat data siswa.', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Terjadi kesalahan saat memuat data siswa.',
                        'error');
                    }
                });
            });

            // Proses update siswa
            $('#editSiswaButton').click(function() {
                var id = $('#editId').val();
                var formData = $('#editSiswaForm').serialize();
                $.ajax({
                    url: '/siswa/' + id,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            hideModal('editSiswaModal');
                            siswaTable.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseText, 'error');
                    }
                });
            });

            // Proses hapus siswa
            $(document).on('click', '.deleteSiswa', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
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
                            success: function(response) {
                                siswaTable.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'Gagal menghapus siswa.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
