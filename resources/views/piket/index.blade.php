@extends('layouts.dashboard')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Manajemen Jadwal Petugas Piket</title>
        <!-- Tailwind CSS via CDN -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
        <!-- Axios -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="bg-gray-100">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-4">Manajemen Jadwal Petugas Piket</h1>
            <button id="addBtn" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mb-4">Tambah Jadwal
                Piket</button>
            <table class="min-w-full bg-white shadow rounded">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Guru</th>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Jam Mulai</th>
                        <th class="px-4 py-2 border">Jam Selesai</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody id="scheduleTable">
                    <!-- Data jadwal akan dimuat via Ajax -->
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah/Edit Jadwal -->
        <div id="scheduleModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                <h2 id="modalTitle" class="text-xl font-bold mb-4">Tambah Jadwal Piket</h2>
                <form id="scheduleForm">
                    <input type="hidden" id="scheduleId" name="id">
                    <div class="mb-3">
                        <label class="block text-gray-700">Guru</label>
                        <select id="guru_id" name="guru_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih Guru</option>
                            @foreach (\App\Models\User::where('role', 'guru')->get() as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-700">Tanggal Piket</label>
                        <input type="date" id="schedule_date" name="schedule_date"
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-700">Jam Mulai</label>
                        <input type="time" id="start_time" name="start_time" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-3">
                        <label class="block text-gray-700">Jam Selesai</label>
                        <input type="time" id="end_time" name="end_time" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="hideModal('scheduleModal')"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">Batal</button>
                        <button type="submit" id="saveScheduleBtn"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Pastikan Axios mengirim header Ajax
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

            function loadSchedules() {
                axios.get('{{ route('piket.index') }}')
                    .then(response => {
                        const schedules = response.data.schedules;
                        let html = '';
                        schedules.forEach((sch, index) => {
                            html += `<tr class="border-b">
              <td class="px-4 py-2">${index + 1}</td>
              <td class="px-4 py-2">${sch.guru.name}</td>
              <td class="px-4 py-2">${sch.schedule_date}</td>
              <td class="px-4 py-2">${sch.start_time ? sch.start_time : '-'}</td>
              <td class="px-4 py-2">${sch.end_time ? sch.end_time : '-'}</td>
              <td class="px-4 py-2">
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded mr-2" onclick="editSchedule(${sch.id})">Edit</button>
                <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded" onclick="deleteSchedule(${sch.id})">Hapus</button>
              </td>
            </tr>`;
                        });
                        $('#scheduleTable').html(html);
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Gagal memuat data jadwal piket.', 'error');
                    });
            }

            function showModal(id) {
                $('#' + id).removeClass('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function hideModal(id) {
                $('#' + id).addClass('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            $('#addBtn').click(function() {
                $('#scheduleForm')[0].reset();
                $('#modalTitle').text('Tambah Jadwal Piket');
                $('#scheduleId').val('');
                showModal('scheduleModal');
            });

            $('#scheduleForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#scheduleId').val();
                const formData = {
                    guru_id: $('#guru_id').val(),
                    schedule_date: $('#schedule_date').val(),
                    start_time: $('#start_time').val(),
                    end_time: $('#end_time').val()
                };
                if (id === '') {
                    axios.post('{{ route('piket.store') }}', formData)
                        .then(response => {
                            Swal.fire('Berhasil!', response.data.message, 'success');
                            hideModal('scheduleModal');
                            loadSchedules();
                        })
                        .catch(error => {
                            Swal.fire('Gagal!', error.response.data.message || 'Terjadi kesalahan.', 'error');
                        });
                } else {
                    axios.put(`/piket/${id}`, formData)
                        .then(response => {
                            Swal.fire('Berhasil!', response.data.message, 'success');
                            hideModal('scheduleModal');
                            loadSchedules();
                        })
                        .catch(error => {
                            Swal.fire('Gagal!', error.response.data.message || 'Terjadi kesalahan.', 'error');
                        });
                }
            });

            function editSchedule(id) {
                axios.get(`/piket/${id}`)
                    .then(response => {
                        const sch = response.data.schedule;
                        $('#scheduleId').val(sch.id);
                        $('#guru_id').val(sch.guru_id);
                        $('#schedule_date').val(sch.schedule_date);
                        $('#start_time').val(sch.start_time);
                        $('#end_time').val(sch.end_time);
                        $('#modalTitle').text('Edit Jadwal Piket');
                        showModal('scheduleModal');
                    })
                    .catch(error => {
                        Swal.fire('Gagal!', 'Gagal mengambil data jadwal piket.', 'error');
                    });
            }

            function deleteSchedule(id) {
                Swal.fire({
                    title: 'Hapus jadwal?',
                    text: 'Anda yakin ingin menghapus jadwal piket ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/piket/${id}`)
                            .then(response => {
                                Swal.fire('Berhasil!', response.data.message, 'success');
                                loadSchedules();
                            })
                            .catch(error => {
                                Swal.fire('Gagal!', error.response.data.message || 'Terjadi kesalahan.', 'error');
                            });
                    }
                });
            }

            $(document).ready(function() {
                loadSchedules();
            });
        </script>
    </body>

    </html>
@endsection
