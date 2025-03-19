@extends('layouts.dashboard')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <main class="container mx-auto px-4 py-8">
        <h2 class="mb-6 text-2xl font-semibold text-gray-700 dark:text-gray-100">
            Manajemen Data Guru
        </h2>

        <!-- Tombol Tambah Guru -->
        <div class="flex flex-col sm:flex-row sm:justify-between mb-6">
            <button onclick="showModal('modalAddGuru')"
                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 mb-4 sm:mb-0">
                Tambah Guru
            </button>
        </div>

        <!-- Tabel Data Guru -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg">
            <table id="guruTable" class="min-w-full">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">No</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Nama Guru</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Email</th>
                        <th class="px-4 py-2 text-center text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300">

                </tbody>
            </table>
        </div>

        <!-- Modal Tambah Guru -->
        <div id="modalAddGuru"
            class="fixed inset-0 flex items-center justify-center hidden z-50 bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-xl w-full p-6">
                <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-gray-100">Tambah Guru</h2>
                <form id="formAddGuru" class="space-y-4">
                    @csrf
                    <div>
                        <label for="add_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                            Guru</label>
                        <input type="text" id="add_name" name="name" class="w-full border rounded-md p-2" required>
                    </div>
                    <div>
                        <label for="add_email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" id="add_email" name="email" class="w-full border rounded-md p-2" required>
                    </div>
                    <div>
                        <label for="add_password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <input type="password" id="add_password" name="password" class="w-full border rounded-md p-2"
                            required>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="hideModal('modalAddGuru')"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Edit Guru -->
        <div id="modalEditGuru"
            class="fixed inset-0 flex items-center justify-center hidden z-50 bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-xl w-full p-6">
                <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-gray-100">Edit Guru</h2>
                <form id="formEditGuru" class="space-y-4">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                            Guru</label>
                        <input type="text" id="edit_name" name="name" class="w-full border rounded-md p-2" required>
                    </div>
                    <div>
                        <label for="edit_email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" id="edit_email" name="email" class="w-full border rounded-md p-2" required>
                    </div>
                    <div>
                        <label for="edit_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password Baru
                        </label>
                        <input type="password" id="edit_password" name="password" class="w-full border rounded-md p-2"
                            placeholder="Masukkan password baru jika ingin mengganti">
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="hideModal('modalEditGuru')"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Batal
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>
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
            // Initialize DataTable for guru
            var guruTable = $('#guruTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('fetchguru') }}',
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
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    searchPlaceholder: "Cari data guru...",
                    search: ""
                },
                responsive: true,
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });

            // Add Guru
            $('#formAddGuru').on('submit', function(event) {
                event.preventDefault();
                var formData = {
                    name: $('#add_name').val(),
                    email: $('#add_email').val(),
                    password: $('#add_password').val()
                };
                $.ajax({
                    url: '/guru',
                    type: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.success) {
                            hideModal('modalAddGuru');
                            $('#formAddGuru')[0].reset();
                            guruTable.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data guru berhasil disimpan',
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

            // Open modal edit guru and populate data
            $(document).on('click', '.editGuru', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/guru/' + id,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#edit_id').val(response.data.id);
                            $('#edit_name').val(response.data.name);
                            $('#edit_email').val(response.data.email);
                            $('#edit_password').val(''); // Kosongkan password
                            showModal('modalEditGuru');
                        } else {
                            Swal.fire('Error', 'Data guru tidak ditemukan', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Terjadi kesalahan saat mengambil data guru',
                            'error');
                    }
                });
            });

            // Update Guru
            $('#formEditGuru').on('submit', function(event) {
                event.preventDefault();
                var id = $('#edit_id').val();
                var formData = {
                    name: $('#edit_name').val(),
                    email: $('#edit_email').val(),
                    password: $('#edit_password').val() // Jika kosong, server akan mengabaikannya
                };
                $.ajax({
                    url: '/guru/' + id,
                    type: 'PUT',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.success) {
                            hideModal('modalEditGuru');
                            guruTable.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data guru berhasil diperbarui',
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

            // Delete Guru
            $(document).on('click', '.deleteGuru', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus data guru ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/guru/' + id,
                            type: 'DELETE',
                            success: function(response) {
                                guruTable.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data guru berhasil dihapus',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'Gagal menghapus data guru',
                                    'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
