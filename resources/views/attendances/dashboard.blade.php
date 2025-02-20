@extends('layouts.dashboard')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard Absensi Petugas Piket</title>
        <!-- Tailwind CSS via CDN -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
        <!-- Axios CDN -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="bg-gray-100">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-4">Dashboard Absensi Petugas Piket</h1>
            <button id="refreshAttendance" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-4">Refresh
                Data</button>
            <table class="min-w-full bg-white shadow rounded">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border">Peran</th>
                        <th class="px-4 py-2 border">Waktu</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody id="attendanceTable">
                    <!-- Data absensi akan dimuat via Ajax -->
                </tbody>
            </table>
        </div>

        <!-- Modal Edit Absensi -->
        <div id="editAttendanceModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                <h2 class="text-xl font-bold mb-4">Edit Absensi</h2>
                <form id="editAttendanceForm">
                    <input type="hidden" id="attendanceId" name="id">
                    <div class="mb-3">
                        <label class="block text-sm font-medium">Status</label>
                        <select id="attendanceStatus" name="status" class="w-full border rounded px-3 py-2">
                            <option value="Hadir">Hadir</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Alfa">Alfa</option>
                            <option value="Terlambat">Terlambat</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="hideModal('editAttendanceModal')"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">Batal</button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Hapus Absensi -->
        <div id="deleteAttendanceModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                <h2 class="text-xl font-bold mb-4">Hapus Absensi</h2>
                <p class="mb-4">Apakah Anda yakin ingin menghapus data absensi ini?</p>
                <div class="flex justify-end">
                    <button type="button" onclick="hideModal('deleteAttendanceModal')"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">Batal</button>
                    <button id="deleteAttendanceButton"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Hapus</button>
                </div>
            </div>
        </div>

        <input type="hidden" id="deleteAttendanceId">
        <script>
            function showModal(id) {
                $('#' + id).removeClass('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function hideModal(id) {
                $('#' + id).addClass('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            function loadAttendance() {
                axios.get('{{ route('attendance.index') }}')
                    .then(response => {
                        const attendances = response.data.attendances;
                        let html = '';
                        attendances.forEach(a => {
                            html += `<tr class="border-b">
              <td class="px-4 py-2">${a.id}</td>
              <td class="px-4 py-2">${a.siswa ? a.siswa.name : (a.guru ? a.guru.name : '')}</td>
              <td class="px-4 py-2">${a.siswa ? 'Siswa' : 'Guru'}</td>
              <td class="px-4 py-2">${a.waktu}</td>
              <td class="px-4 py-2">${a.status}</td>
              <td class="px-4 py-2">
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded mr-2" onclick="editAttendance(${a.id})">Edit</button>
                <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded" onclick="deleteAttendance(${a.id})">Hapus</button>
              </td>
            </tr>`;
                        });
                        $('#attendanceTable').html(html);
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Gagal memuat data absensi.', 'error');
                    });
            }

            function editAttendance(id) {
                axios.get(`/attendance/${id}`)
                    .then(response => {
                        const attendance = response.data.attendance;
                        $('#attendanceId').val(attendance.id);
                        $('#attendanceStatus').val(attendance.status);
                        showModal('editAttendanceModal');
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Gagal mengambil data absensi.', 'error');
                    });
            }

            $('#editAttendanceForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#attendanceId').val();
                axios.put(`/attendance/${id}`, {
                        status: $('#attendanceStatus').val()
                    })
                    .then(response => {
                        Swal.fire('Berhasil!', response.data.message, 'success');
                        hideModal('editAttendanceModal');
                        loadAttendance();
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Gagal memperbarui data absensi.', 'error');
                    });
            });

            function deleteAttendance(id) {
                $('#deleteAttendanceId').val(id);
                showModal('deleteAttendanceModal');
            }

            $('#deleteAttendanceButton').on('click', function() {
                const id = $('#deleteAttendanceId').val();
                axios.delete(`/attendance/${id}`)
                    .then(response => {
                        Swal.fire('Berhasil!', response.data.message, 'success');
                        hideModal('deleteAttendanceModal');
                        loadAttendance();
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Gagal menghapus data absensi.', 'error');
                    });
            });

            $(document).ready(function() {
                loadAttendance();

                $('#refreshAttendance').on('click', function() {
                    loadAttendance();
                });
            });
        </script>
    </body>

    </html>
@endsection
