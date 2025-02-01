<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Siswa</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-2xl font-bold text-center mb-6">Absensi Siswa</h1>
        <table class="table-auto w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Kelas</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">foto wajah</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $item)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $item->nama }}</td>
                        <td class="px-4 py-2">{{ $item->kelas->kelas }}</td>
                        {{-- <td class="px-4 py-2">{{ $item->foto_wajah }}</td> --}}
                        <img src="data:image/png;base64,{{ $item->foto_wajah }}" alt="Foto Wajah"
                            class="w-100 h-100 rounded-full">
                        <td class="px-4 py-2">
                            <select id="status-{{ $item->id }}" class="border rounded px-2 py-1">
                                <option value="Hadir">Hadir</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Izin">Izin</option>
                                <option value="Alfa">Alfa</option>
                            </select>
                        </td>
                        <td class="px-4 py-2">
                            <button onclick="submitAttendance({{ $item->id }})"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">
                                Simpan
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function submitAttendance(siswaId) {
            const status = document.getElementById(`status-${siswaId}`).value;

            axios.post('{{ route('attendances.store') }}', {
                    siswa_id: siswaId,
                    status: status,
                    lokasi: 'Latitude, Longitude', // Tambahkan lokasi dari GPS jika tersedia
                })
                .then(response => {
                    Swal.fire('Berhasil!', response.data.message, 'success');
                })
                .catch(error => {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan absensi.', 'error');
                });
        }
    </script>
</body>

</html>
