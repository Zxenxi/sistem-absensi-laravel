@extends('layouts.dashboard')

@section('content')
    <!DOCTYPE html>
    <html lang="en" class="dark">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Dashboard Absensi Petugas Piket</title>
        <!-- Tailwind CSS -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet" />
        <!-- Axios -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <style>
            /* Responsive table styling */
            @media (max-width: 640px) {
                .responsive-table thead {
                    display: none;
                }

                .responsive-table tr {
                    display: block;
                    margin-bottom: 0.625rem;
                }

                .responsive-table td {
                    display: block;
                    text-align: right;
                    font-size: 0.8rem;
                    border-bottom: 1px solid #ddd;
                    position: relative;
                    padding-left: 50%;
                }

                .responsive-table td::before {
                    content: attr(data-label);
                    position: absolute;
                    left: 0;
                    width: 45%;
                    padding-left: 15px;
                    font-weight: bold;
                    text-align: left;
                }
            }
        </style>
    </head>

    <body class="bg-gray-100 dark:bg-gray-900">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">Dashboard Absensi Petugas Piket</h1>
            <div class="mb-4 flex justify-between items-center">
                <button id="refreshBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow-md">
                    Refresh Data
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="responsive-table min-w-full bg-white dark:bg-gray-800 dark:text-white shadow rounded">
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Foto</th>
                            <th class="px-4 py-2 border">Nama</th>
                            <th class="px-4 py-2 border">Peran</th>
                            <th class="px-4 py-2 border">Waktu</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTable">
                        @foreach ($attendances as $attendance)
                            @php
                                $peran = $attendance->siswa_id ? 'Siswa' : 'Guru';
                                $nama = $attendance->siswa
                                    ? $attendance->siswa->name
                                    : ($attendance->guru
                                        ? $attendance->guru->name
                                        : '');
                                $foto = $attendance->foto_wajah ? asset($attendance->foto_wajah) : null;
                            @endphp
                            <tr class="border-b">
                                <td class="px-4 py-2" data-label="ID">{{ $attendance->id }}</td>
                                <td class="px-4 py-2" data-label="Foto">
                                    @if ($foto)
                                        <img src="{{ $foto }}" alt="Foto Wajah"
                                            class="w-12 h-12 object-cover rounded" />
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2" data-label="Nama">{{ $nama }}</td>
                                <td class="px-4 py-2" data-label="Peran">{{ $peran }}</td>
                                <td class="px-4 py-2" data-label="Waktu">{{ $attendance->waktu }}</td>
                                <td class="px-4 py-2" data-label="Status">{{ $attendance->status }}</td>
                                <td class="px-4 py-2" data-label="Aksi">
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded mr-2"
                                        onclick="editAttendance({{ $attendance->id }})">Edit</button>
                                    <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded"
                                        onclick="deleteAttendance({{ $attendance->id }})">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Edit Absensi -->
        <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-96 p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Edit Absensi</h2>
                <form id="editForm">
                    <input type="hidden" id="attendanceId" name="id" />
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="attendanceStatus" name="status"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:text-gray-200">
                            <option value="Hadir">Hadir</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Alfa">Alfa</option>
                            <option value="Terlambat">Terlambat</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="hideModal('editModal')"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">Batal</button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Hapus Absensi -->
        <div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-96 p-6">
                <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Hapus Absensi</h2>
                <p class="mb-4 text-gray-700 dark:text-gray-300">Apakah Anda yakin ingin menghapus data absensi ini?</p>
                <div class="flex justify-end">
                    <button type="button" onclick="hideModal('deleteModal')"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">Batal</button>
                    <button id="deleteAttendanceBtn"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Hapus</button>
                </div>
            </div>
        </div>

        <input type="hidden" id="deleteAttendanceId" />

        <script>
            // Pastikan Axios mengirim header sebagai Ajax
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

            function showModal(id) {
                document.getElementById(id).classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function hideModal(id) {
                document.getElementById(id).classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            // Fungsi untuk refresh data absensi (menggunakan Ajax)
            function refreshAttendance() {
                axios.get('{{ route('attendance.index') }}')
                    .then(response => {
                        const attendances = response.data.attendances;
                        let html = '';
                        attendances.forEach(a => {
                            const peran = a.siswa_id ? 'Siswa' : 'Guru';
                            const foto = a.foto_wajah ?
                                `<img src="${a.foto_wajah}" class="w-16 h-16 object-cover rounded" alt="Foto Wajah">` :
                                `<span class="text-gray-500 dark:text-gray-400">-</span>`;
                            const nama = a.siswa ? a.siswa.name : (a.guru ? a.guru.name : '');
                            html += `<tr class="border-b">
              <td class="px-4 py-2" data-label="ID">${a.id}</td>
              <td class="px-4 py-2" data-label="Foto">${foto}</td>
              <td class="px-4 py-2" data-label="Nama">${nama}</td>
              <td class="px-4 py-2" data-label="Peran">${peran}</td>
              <td class="px-4 py-2" data-label="Waktu">${a.waktu}</td>
              <td class="px-4 py-2" data-label="Status">${a.status}</td>
              <td class="px-4 py-2" data-label="Aksi">
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded mr-2" onclick="editAttendance(${a.id})">Edit</button>
                <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded" onclick="deleteAttendance(${a.id})">Hapus</button>
              </td>
            </tr>`;
                        });
                        document.getElementById('attendanceTable').innerHTML = html;
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Gagal memuat data absensi.', 'error');
                    });
            }

            document.getElementById('refreshBtn').addEventListener('click', refreshAttendance);

            function editAttendance(id) {
                axios.get(`/attendance/${id}`)
                    .then(response => {
                        const attendance = response.data.attendance;
                        document.getElementById('attendanceId').value = attendance.id;
                        document.getElementById('attendanceStatus').value = attendance.status;
                        showModal('editModal');
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Gagal mengambil data absensi.', 'error');
                    });
            }

            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const id = document.getElementById('attendanceId').value;
                axios.put(`/attendance/${id}`, {
                        status: document.getElementById('attendanceStatus').value
                    })
                    .then(response => {
                        Swal.fire('Berhasil!', response.data.message, 'success');
                        hideModal('editModal');
                        refreshAttendance();
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Gagal memperbarui data absensi.', 'error');
                    });
            });

            function deleteAttendance(id) {
                document.getElementById('deleteAttendanceId').value = id;
                showModal('deleteModal');
            }

            document.getElementById('deleteAttendanceBtn').addEventListener('click', function() {
                const id = document.getElementById('deleteAttendanceId').value;
                axios.delete(`/attendance/${id}`)
                    .then(response => {
                        Swal.fire('Berhasil!', response.data.message, 'success');
                        hideModal('deleteModal');
                        refreshAttendance();
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Gagal menghapus data absensi.', 'error');
                    });
            });

            document.addEventListener('DOMContentLoaded', refreshAttendance);
        </script>
    </body>

    </html>
@endsection
