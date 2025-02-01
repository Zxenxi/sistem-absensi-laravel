<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6">Form Absensi</h1>

            <!-- Form Absensi -->
            <form id="absensiForm" class="space-y-4">
                <!-- Dropdown Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="role" name="role"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                    </select>
                </div>

                <!-- ID -->
                <div>
                    <label for="id" class="block text-sm font-medium text-gray-700">ID (NISN/Nama Guru)</label>
                    <input type="text" id="id" name="id"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan ID Anda" required>
                </div>

                <!-- Kamera -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ambil Foto</label>
                    <div class="border rounded-md overflow-hidden relative">
                        <video id="video" class="w-full h-auto"></video>
                        <canvas id="canvas" class="hidden"></canvas>
                        <img id="photoPreview" class="hidden w-full h-auto border rounded-md" alt="Foto Wajah">
                        <button id="takePhoto" type="button"
                            class="absolute bottom-2 right-2 bg-blue-500 text-white py-1 px-2 rounded">Ambil
                            Foto</button>
                    </div>
                </div>

                <!-- Lokasi -->
                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <input type="text" id="lokasi" name="lokasi"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Lokasi Anda" readonly required>
                </div>

                <!-- Tombol Absensi -->
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                        Absen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // MediaDevices API: Akses kamera perangkat
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const takePhotoButton = document.getElementById('takePhoto');
        const photoPreview = document.getElementById('photoPreview');

        let photoData = null;

        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then((stream) => {
                video.srcObject = stream;
                video.play();
            })
            .catch((err) => {
                Swal.fire('Gagal!', 'Kamera tidak bisa diakses.', 'error');
            });

        takePhotoButton.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            photoData = canvas.toDataURL('image/png'); // Konversi foto ke Base64
            photoPreview.src = photoData;
            photoPreview.classList.remove('hidden');
            video.classList.add('hidden'); // Sembunyikan video
            takePhotoButton.classList.add('hidden'); // Sembunyikan tombol ambil foto
        });

        // Ambil lokasi GPS
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('lokasi').value =
                        `${position.coords.latitude}, ${position.coords.longitude}`;
                },
                function() {
                    Swal.fire('Gagal!', 'Lokasi GPS tidak bisa diakses.', 'error');
                }
            );
        } else {
            Swal.fire('Gagal!', 'Browser Anda tidak mendukung GPS.', 'error');
        }

        // Proses Absensi
        document.getElementById('absensiForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validasi
            if (!document.getElementById('id').value) {
                Swal.fire('Gagal!', 'ID harus diisi.', 'error');
                return;
            }
            if (!photoData) {
                Swal.fire('Gagal!', 'Anda harus mengambil foto.', 'error');
                return;
            }

            // Kirim data ke server
            axios.post('{{ route('absensi.store') }}', {
                    role: document.getElementById('role').value,
                    id: document.getElementById('id').value,
                    lokasi: document.getElementById('lokasi').value,
                    foto_wajah: photoData,
                })
                .then(response => {
                    Swal.fire('Berhasil!', response.data.message, 'success');
                    document.getElementById('absensiForm').reset();
                    photoPreview.classList.add('hidden');
                    video.classList.remove('hidden'); // Tampilkan kembali video
                    takePhotoButton.classList.remove('hidden'); // Tampilkan kembali tombol ambil foto
                })
                .catch(error => {
                    Swal.fire('Gagal!', error.response.data.message || 'Terjadi kesalahan.', 'error');
                });
        });
    </script>
</body>

</html>
