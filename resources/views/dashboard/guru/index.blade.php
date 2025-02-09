@extends('layouts.dashboard')

@section('content')
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Manajemen Data Guru</h2>
        <!-- Tombol Tambah Guru -->
        <div class="flex justify-between mb-8">
            <button onclick="showModal('modalAddGuru')"
                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                Tambah Guru
            </button>
        </div>

        <!-- Tabel Data Guru -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Nama Guru</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="guruTable" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Data akan dimuat dengan AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Notifikasi -->
        <div id="notification"
            class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hidden transition-opacity duration-300">
            <span id="notificationMessage"></span>
        </div>

        <!-- Modal Tambah Guru -->
        <div id="modalAddGuru"
            class="fixed inset-0 flex items-center justify-center hidden z-50 bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-xl p-6 w-full">
                <h2 class="text-xl font-bold mb-4">Tambah Guru</h2>
                <form id="formAddGuru" class="space-y-4">
                    @csrf
                    <div>
                        <label for="add_name" class="block text-sm font-medium">Nama Guru</label>
                        <input type="text" id="add_name" name="name" class="w-full border rounded-md p-2" required>
                    </div>
                    <div>
                        <label for="add_email" class="block text-sm font-medium">Email</label>
                        <input type="email" id="add_email" name="email" class="w-full border rounded-md p-2" required>
                    </div>
                    <div>
                        <label for="add_password" class="block text-sm font-medium">Password</label>
                        <input type="password" id="add_password" name="password" class="w-full border rounded-md p-2"
                            required>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="hideModal('modalAddGuru')"
                            class="bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Guru -->
        <div id="modalEditGuru"
            class="fixed inset-0 flex items-center justify-center hidden z-50 bg-black bg-opacity-50 backdrop-blur-sm ">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-xl p-6 w-full">
                <h2 class="text-xl font-bold mb-4">Edit Guru</h2>
                <form id="formEditGuru" class="space-y-4">
                    @csrf
                    <input type="hidden" id="edit_id">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium">Nama Guru</label>
                        <input type="text" id="edit_name" name="name" class="w-full border rounded-md p-2" required>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="hideModal('modalEditGuru')"
                            class="bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Hapus Guru -->
        <div id="modalDeleteGuru"
            class="fixed inset-0 flex items-center justify-center hidden z-50 bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md p-6 w-full">
                <h2 class="text-xl font-bold mb-4">Konfirmasi Hapus</h2>
                <p class="mb-6">Apakah Anda yakin ingin menghapus data guru ini?</p>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="hideModal('modalDeleteGuru')"
                        class="bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
                    <button type="button" id="confirmDeleteGuru"
                        class="bg-red-600 text-white px-4 py-2 rounded">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi notifikasi
        function notification(message, type = 'success') {
            const notif = document.getElementById('notification');
            document.getElementById('notificationMessage').textContent = message;
            notif.classList.remove('hidden', 'bg-green-500', 'bg-red-500');
            notif.classList.add(type === 'success' ? 'bg-green-500' : 'bg-red-500');
            setTimeout(() => {
                notif.classList.add('hidden');
            }, 3000);
        }

        // Fungsi show/hide modal
        function showModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function hideModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Mengambil data guru
        function fetchGuru() {
            fetch('{{ route('fetchguru') }}')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('guruTable');
                    tbody.innerHTML = '';
                    data.data.forEach((guru, index) => {
                        tbody.innerHTML += `
                        <tr>
                            <td class="px-4 py-2">${index + 1}</td>
                            <td class="px-4 py-2">${guru.name}</td>
                            <td class="px-4 py-2">${guru.email}</td>
                            <td class="px-4 py-2 text-center">
                                <button onclick="editGuru(${guru.id})" class="px-4 py-2 bg-blue-600 text-white rounded">Edit</button>
                                <button onclick="deleteGuru(${guru.id})" class="px-4 py-2 bg-red-600 text-white rounded">Hapus</button>
                            </td>
                        </tr>
                    `;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    notification('Terjadi kesalahan saat mengambil data guru', 'error');
                });
        }

        // Tambah guru
        document.getElementById('formAddGuru').addEventListener('submit', function(event) {
            event.preventDefault();
            const name = document.getElementById('add_name').value;
            const email = document.getElementById('add_email').value;
            const password = document.getElementById('add_password').value;

            fetch('/guru', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name,
                        email,
                        password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchGuru();
                        hideModal('modalAddGuru');
                        notification('Data guru berhasil disimpan', 'success');
                    } else {
                        notification('Gagal menyimpan data guru', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    notification('Terjadi kesalahan saat menyimpan data guru', 'error');
                });
        });

        // Buka modal edit dan isi data
        function editGuru(id) {
            fetch(`/guru/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const guru = data.data;
                        document.getElementById('edit_id').value = guru.id;
                        document.getElementById('edit_name').value = guru.name;
                        showModal('modalEditGuru');
                    } else {
                        notification('Data guru tidak ditemukan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    notification('Terjadi kesalahan saat mengambil data guru', 'error');
                });
        }

        // Update guru
        document.getElementById('formEditGuru').addEventListener('submit', function(event) {
            event.preventDefault();
            const id = document.getElementById('edit_id').value;
            const name = document.getElementById('edit_name').value;

            fetch(`/guru/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchGuru();
                        hideModal('modalEditGuru');
                        notification('Data guru berhasil diperbarui', 'success');
                    } else {
                        notification('Gagal memperbarui data guru', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    notification('Terjadi kesalahan saat memperbarui data guru', 'error');
                });
        });

        // Hapus guru: buka modal konfirmasi hapus
        let guruToDelete = null;

        function deleteGuru(id) {
            guruToDelete = id;
            showModal('modalDeleteGuru');
        }

        // Konfirmasi hapus guru
        document.getElementById('confirmDeleteGuru').addEventListener('click', function() {
            if (guruToDelete) {
                fetch(`/guru/${guruToDelete}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            fetchGuru();
                            hideModal('modalDeleteGuru');
                            notification('Data guru berhasil dihapus', 'success');
                        } else {
                            notification('Gagal menghapus data guru', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        notification('Terjadi kesalahan saat menghapus data guru', 'error');
                    });
            }
        });

        // Panggil data saat halaman pertama kali dimuat
        fetchGuru();
    </script>
@endsection
