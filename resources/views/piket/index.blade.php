@extends('layouts.dashboard')

@section('content')
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-100">
            Manajemen Jadwal Petugas Piket
        </h1>
        <!-- Tombol Tambah Jadwal -->
        <button id="addBtn" class="bg-blue-600 hover:bg-green-600 text-white px-4 py-2 rounded mb-4 w-full sm:w-auto">
            Tambah Jadwal Piket
        </button>
        <!-- Tabel Jadwal -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
            <table class="min-w-full">
                <thead class="bg-gray-100 dark:bg-gray-700 border-b">
                    <tr>
                        <th class="px-4 py-2 border text-left text-gray-700 dark:text-gray-300">No</th>
                        <th class="px-4 py-2 border text-left text-gray-700 dark:text-gray-300">Guru</th>
                        <th class="px-4 py-2 border text-left text-gray-700 dark:text-gray-300">Tanggal</th>
                        <th class="px-4 py-2 border text-left text-gray-700 dark:text-gray-300">Jam Mulai</th>
                        <th class="px-4 py-2 border text-left text-gray-700 dark:text-gray-300">Jam Selesai</th>
                        <th class="px-4 py-2 border text-center text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody id="scheduleTable" class="divide-y divide-gray-200 text-gray-700 dark:text-gray-300">
                    <!-- Data jadwal akan dimuat via AJAX -->
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal: Tambah/Edit Jadwal -->
    <!-- Modal: Tambah/Edit Jadwal -->
    <div id="scheduleModal"
        class="fixed inset-0 flex items-center justify-center hidden z-50 bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 id="modalTitle" class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Tambah Jadwal Piket</h2>
            <form id="scheduleForm" class="space-y-4">
                <input type="hidden" id="scheduleId" name="id">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guru</label>
                    <select id="guru_id" name="guru_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">Pilih Guru</option>
                        @foreach (\App\Models\User::where('role', 'guru')->get() as $guru)
                            <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Piket</label>
                    <input type="date" id="schedule_date" name="schedule_date" class="w-full border rounded px-3 py-2"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Mulai</label>
                    <input type="time" id="start_time" name="start_time" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Selesai</label>
                    <input type="time" id="end_time" name="end_time" class="w-full border rounded px-3 py-2">
                </div>
                <!-- Checkbox Repeat Weekly -->
                <div class="mb-3 flex items-center">
                    <input type="checkbox" id="repeat_weekly" name="repeat_weekly" class="mr-2">
                    <label for="repeat_weekly" class="text-sm text-gray-700 dark:text-gray-300">Repeat Weekly (Ulang setiap
                        minggu)</label>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-end gap-4">
                    <button type="button" onclick="hideModal('scheduleModal')"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded w-full sm:w-auto">
                        Batal
                    </button>
                    <button type="submit" id="saveScheduleBtn"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded w-full sm:w-auto">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Pastikan Axios, jQuery, dan SweetAlert2 sudah dimuat -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Setup global AJAX (Axios and jQuery)
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
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

        // Load jadwal piket via Axios
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
            <td class="px-4 py-2 text-center">
              <button class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded mr-2" onclick="editSchedule(${sch.id})">Edit</button>
              <button class="bg-red-600 hover:bg-red-600 text-white px-2 py-1 rounded" onclick="deleteSchedule(${sch.id})">Hapus</button>
            </td>
          </tr>`;
                    });
                    $('#scheduleTable').html(html);
                })
                .catch(error => {
                    Swal.fire('Error!', 'Gagal memuat data jadwal piket.', 'error');
                });
        }

        // Show/hide modal
        $(document).ready(function() {
            loadSchedules();

            $('#addBtn').click(function() {
                $('#scheduleForm')[0].reset();
                $('#modalTitle').text('Tambah Jadwal Piket');
                $('#scheduleId').val('');
                showModal('scheduleModal');
            });

            // Form submit (tambah/edit jadwal)
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
                            Swal.fire('Gagal!', error.response.data.message || 'Terjadi kesalahan.',
                                'error');
                        });
                } else {
                    axios.put(`/piket/${id}`, formData)
                        .then(response => {
                            Swal.fire('Berhasil!', response.data.message, 'success');
                            hideModal('scheduleModal');
                            loadSchedules();
                        })
                        .catch(error => {
                            Swal.fire('Gagal!', error.response.data.message || 'Terjadi kesalahan.',
                                'error');
                        });
                }
            });
        });

        // Edit jadwal
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

        // Delete jadwal
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
    </script>
@endsection
