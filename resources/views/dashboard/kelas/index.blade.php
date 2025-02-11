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
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">#</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Kelas</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Jurusan</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Tahun Ajaran</th>
                        <th class="px-4 py-2 text-center text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kelasTable" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Data akan dimuat dengan AJAX -->
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

    <!-- Script -->
    <script>
        // Pastikan SweetAlert2 sudah dimuat, misalnya dengan:
        // <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">


        // Load Data Kelas
        function fetchKelas() {
            fetch('{{ route('kelas.fetch') }}')
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    const tbody = document.getElementById('kelasTable');
                    tbody.innerHTML = '';
                    data.data.forEach((kelas, index) => {
                        tbody.innerHTML += `
    <tr>
        <td class='px-4 py-2 text-gray-700 dark:text-gray-300'>${index + 1}</td>
        <td class='px-4 py-2 text-gray-700 dark:text-gray-300'>${kelas.kelas}</td>
        <td class='px-4 py-2 text-gray-700 dark:text-gray-300'>${kelas.jurusan}</td>
        <td class='px-4 py-2 text-gray-700 dark:text-gray-300'>${kelas.tahun_ajaran}</td>
        <td class='px-4 py-2 text-center'>
            <button onclick='editKelas(${kelas.id})'
                class='bg-blue-600 hover:bg-yellow-600 text-white px-4 py-2 rounded-md dark:bg-blue-700 dark:hover:bg-yellow-700'>Edit</button>
            <button onclick='confirmDelete(${kelas.id})'
                class='bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md dark:bg-red-700 dark:hover:bg-red-800'>Hapus</button>
        </td>
    </tr>`;
                    });
                })
                .catch(error => {
                    console.error('Error fetching kelas:', error);
                    Swal.fire('Error', 'Terjadi kesalahan saat memuat data kelas.', 'error');
                });
        }

        // Modal Control
        function showModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Mencegah scroll saat modal muncul
        }

        function hideModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.classList.remove('overflow-hidden'); // Mengembalikan scroll setelah modal ditutup
        }

        // Tambah/Edit Kelas
        document.getElementById('formKelas').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('id_kelas').value;
            const url = id ? `/kelas/${id}` : '/kelas';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        kelas: document.getElementById('kelas').value,
                        jurusan: document.getElementById('jurusan').value,
                        tahun_ajaran: document.getElementById('tahun_ajaran').value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchKelas();
                        hideModal('modalKelas');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data kelas berhasil disimpan.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Error', 'Gagal menyimpan data.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error saving kelas:', error);
                    Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data.', 'error');
                });
        });

        // Edit Data
        function editKelas(id) {
            fetch(`/kelas/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('id_kelas').value = data.data.id;
                        document.getElementById('kelas').value = data.data.kelas;
                        document.getElementById('jurusan').value = data.data.jurusan;
                        document.getElementById('tahun_ajaran').value = data.data.tahun_ajaran;

                        document.getElementById('modalKelasLabel').textContent = 'Edit Kelas';
                        showModal('modalKelas');
                    } else {
                        Swal.fire('Error', 'Gagal memuat data kelas.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error fetching kelas data:', error);
                    Swal.fire('Error', 'Terjadi kesalahan saat memuat data kelas.', 'error');
                });
        }

        // Hapus Data dengan SweetAlert2 Confirmation
        function confirmDelete(id) {
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
                    deleteKelas(id);
                }
            });
        }

        function deleteKelas(id) {
            fetch(`/kelas/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchKelas();
                        Swal.fire('Deleted!', 'Data kelas berhasil dihapus.', 'success');
                    } else {
                        Swal.fire('Error', 'Gagal menghapus data.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting kelas:', error);
                    Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
                });
        }

        // Load Data Saat Halaman Dimuat
        fetchKelas();
    </script>
@endsection
