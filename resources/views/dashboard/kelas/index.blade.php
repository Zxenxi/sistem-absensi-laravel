@extends('layouts.dashboard')

@section('content')
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Manajemen Data Kelas
        </h2>
        <!-- CTA -->
        <div class="flex justify-between mb-8">
            <button onclick="showModal('modalKelas')"
                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple dark:bg-purple-500 dark:hover:bg-purple-600">
                Tambah Kelas
            </button>
        </div>

        <!-- Tabel Data Kelas -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
            <table id="kelasTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">No</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Kelas</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Jurusan</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Tahun Ajaran</th>
                        <th class="px-4 py-2 text-center text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300">
                    <!-- Data dimuat secara dinamis melalui AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah/Edit Kelas -->
        <div id="modalKelas"
            class="fixed inset-0 flex items-center justify-center hidden z-20 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md p-6 shadow-lg w-full max-w-xl">
                <h2 id="modalKelasLabel" class="text-xl font-bold mb-4 text-gray-700 dark:text-gray-200">Tambah Kelas</h2>
                <form id="formKelas" class="space-y-4">
                    @csrf
                    <input type="hidden" id="id_kelas">
                    <div>
                        <label for="kelas"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                        <input type="text" id="kelas" name="kelas"
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800"
                            required>
                    </div>
                    <div>
                        <label for="jurusan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jurusan</label>
                        <input type="text" id="jurusan" name="jurusan"
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800"
                            required>
                    </div>
                    <div>
                        <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun
                            Ajaran</label>
                        <input type="text" id="tahun_ajaran" name="tahun_ajaran"
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800"
                            required>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="hideModal('modalKelas')"
                            class="bg-black hover:bg-gray-600 text-white px-4 py-2 rounded-md dark:bg-gray-700 dark:hover:bg-gray-800">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-green-700 text-white px-4 py-2 rounded-md dark:bg-green-600 dark:hover:bg-green-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery (pastikan dimuat sebelum DataTables) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable menggunakan Yajra DataTables (server-side)
            var table = $('#kelasTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('kelas.fetch') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
                        data: 'tahun_ajaran',
                        name: 'tahun_ajaran'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    searchPlaceholder: "Cari kelas...",
                    search: ""
                },
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            // Modal Control Functions
            window.showModal = function(id) {
                document.getElementById(id).classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            window.hideModal = function(id) {
                document.getElementById(id).classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            // Form Submission untuk Tambah/Edit Kelas
            $('#formKelas').on('submit', function(e) {
                e.preventDefault();
                var id = $('#id_kelas').val();
                var url = id ? '/kelas/' + id : '/kelas';
                var method = id ? 'PUT' : 'POST';
                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        kelas: $('#kelas').val(),
                        jurusan: $('#jurusan').val(),
                        tahun_ajaran: $('#tahun_ajaran').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success) {
                            table.ajax.reload();
                            hideModal('modalKelas');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data.', 'error');
                    }
                });
            });

            // Fungsi Edit Kelas
            window.editKelas = function(id) {
                $.ajax({
                    url: '/kelas/' + id,
                    type: 'GET',
                    success: function(data) {
                        if (data.success) {
                            $('#id_kelas').val(data.data.id);
                            $('#kelas').val(data.data.kelas);
                            $('#jurusan').val(data.data.jurusan);
                            $('#tahun_ajaran').val(data.data.tahun_ajaran);
                            $('#modalKelasLabel').text('Edit Kelas');
                            showModal('modalKelas');
                        } else {
                            Swal.fire('Error', 'Gagal memuat data kelas.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan saat memuat data kelas.', 'error');
                    }
                });
            };

            // Fungsi Hapus Kelas dengan Konfirmasi
            window.confirmDelete = function(id) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/kelas/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.success) {
                                    table.ajax.reload();
                                    Swal.fire('Deleted!', data.message, 'success');
                                } else {
                                    Swal.fire('Error', data.message, 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.',
                                    'error');
                            }
                        });
                    }
                });
            };
        });
    </script>
@endsection
