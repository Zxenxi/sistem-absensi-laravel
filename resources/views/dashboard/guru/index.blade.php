@extends('layouts.dashboard')

@section('content')
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Manajemen Data Guru
        </h2>
        <!-- CTA -->
        <div class="flex justify-between mb-8">
            <button onclick="showModal('modalGuru')"
                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple dark:bg-purple-500 dark:hover:bg-purple-600">
                Tambah Guru
            </button>
        </div>

        <!-- Tabel Data Guru -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">No</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Nama Guru</th>
                        <th class="px-4 py-2 text-center text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody id="guruTable" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Data akan dimuat dengan AJAX -->
                </tbody>
            </table>
        </div>
        <div id="notification"
            class="fixed justify-center bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hidden z-50 transition-opacity duration-300">
            <span id="notificationMessage"></span>
        </div>
        <!-- Modal Tambah Guru -->
        <div id="modalGuru"
            class="fixed inset-0 flex items-center justify-center hidden z-50 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md p-6 shadow-lg w-full max-w-xl">
                <h2 id="modalGuruLabel" class="text-xl font-bold mb-4 text-gray-700 dark:text-gray-200">Tambah Guru</h2>
                <form id="formGuru" class="space-y-4">
                    @csrf
                    <input type="hidden" id="id_guru">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                            Guru</label>
                        <input type="text" id="nama" name="guru"
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800"
                            required>
                    </div>
                    {{-- <div>
                        <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun
                            Ajaran</label>
                        <input type="text" id="tahun_ajaran" name="tahun_ajaran"
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800"
                            required>
                    </div> --}}
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="hideModal('modalGuru')"
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

        <!-- Modal Edit Guru -->
        <div id="modalEditGuru"
            class="fixed inset-0 flex items-center justify-center hidden z-50 bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md p-6 shadow-lg w-full max-w-xl">
                <h2 id="modalEditGuruLabel" class="text-xl font-bold mb-4 text-gray-700 dark:text-gray-200">Edit Guru</h2>
                <form id="formEditGuru" class="space-y-4">
                    @csrf
                    <input type="hidden" id="edit_id_guru">
                    <div>
                        <label for="edit_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                            Guru</label>
                        <input type="text" id="edit_nama" name="guru"
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800"
                            required>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="hideModal('modalEditGuru')"
                            class="bg-black hover:bg-gray-600 text-white px-4 py-2 rounded-md dark:bg-gray-700 dark:hover:bg-gray-800">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-green-700 text-white px-4 py-2 rounded-md dark:bg-green-600 dark:hover:bg-green-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Hapus Guru -->
        <div id="modalHapusGuru"
            class="fixed inset-0 flex items-center justify-center hidden z-50 bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md p-6 shadow-lg w-full max-w-xl">
                <h2 class="text-xl font-bold mb-4 text-gray-700 dark:text-gray-200">Konfirmasi Hapus</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">Apakah Anda yakin ingin menghapus data guru ini?</p>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="hideModal('modalHapusGuru')"
                        class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        Batal
                    </button>
                    <button type="button" id="confirmDeleteGuru"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

    </div>
    <script>
        function notification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const messageSpan = document.getElementById('notificationMessage');

            messageSpan.textContent = message;
            notification.classList.remove('hidden', 'bg-green-500', 'bg-red-500');
            notification.classList.add(type === 'success' ? 'bg-green-500' : 'bg-red-500');

            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }

        function fetchGuru() {
            fetch('{{ route('fetchguru') }}')
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    const tbody = document.getElementById('guruTable');
                    tbody.innerHTML = '';
                    data.data.forEach((guru, index) => {
                        tbody.innerHTML += `
                            <tr>
                                <td class='px-4 py-2 text-gray-700 dark:text-gray-300'>${index + 1}</td>
                                <td class='px-4 py-2 text-gray-700 dark:text-gray-300'>${guru.nama}</td>
                                <td class='px-4 py-2 text-center'>
                                    <button onclick='editGuru(${guru.id})'
                                        class='px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700'>
                                        Edit
                                    </button>
                                    <button onclick='deleteGuru(${guru.id})'
                                        class='px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700'>
                                        Hapus
                                    </button>
                                </td>
                            </tr>`;
                    });
                })
                .catch(error => {
                    console.error('Error fetching data guru:', error);
                    alert('Terjadi kesalahan saat mengambil data guru.');
                });
        }

        function showModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function hideModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.getElementById('formGuru').addEventListener('submit', (event) => {
            event.preventDefault();
            const id = document.getElementById('id_guru').value;
            const url = id ? `/guru/${id}` : `/guru`;
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nama: document.getElementById('nama').value,
                        // tahun_ajaran: document.getElementById('tahun_ajaran').value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchGuru();
                        hideModal('modalGuru');
                        notification('Data guru berhasil disimpan', 'success');
                    } else {
                        notification('Data guru gagal disimpan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error saving data guru:', error);
                    notification('Terjadi kesalahan saat menyimpan data guru', 'error');
                });
        });

        function editGuru(id) {
            fetch(`/guru/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const guru = data.data;
                        document.getElementById('edit_id_guru').value = guru.id;
                        document.getElementById('edit_nama').value = guru.nama;
                        showModal('modalEditGuru');
                    } else {
                        notification('Data guru tidak ditemukan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error fetching guru data:', error);
                    notification('Terjadi kesalahan saat mengambil data guru', 'error');
                });
        }

        document.getElementById('formEditGuru').addEventListener('submit', (event) => {
            event.preventDefault();
            const id = document.getElementById('edit_id_guru').value;
            const nama = document.getElementById('edit_nama').value;

            fetch(`/guru/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nama
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchGuru();
                        hideModal('modalEditGuru');
                        notification('Data guru berhasil diperbarui', 'success');
                    } else {
                        notification('Data guru gagal diperbarui', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error updating guru:', error);
                    notification('Terjadi kesalahan saat memperbarui data guru', 'error');
                });
        });

        let guruToDelete = null;

        function deleteGuru(id) {
            guruToDelete = id;
            showModal('modalHapusGuru');
        }

        document.getElementById('confirmDeleteGuru').addEventListener('click', () => {
            if (!guruToDelete) return;

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
                        hideModal('modalHapusGuru');
                        notification('Data guru berhasil dihapus', 'success');
                    } else {
                        notification('Data guru gagal dihapus', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting guru:', error);
                    notification('Terjadi kesalahan saat menghapus data guru', 'error');
                });
        });

        fetchGuru();
    </script>
@endsection
