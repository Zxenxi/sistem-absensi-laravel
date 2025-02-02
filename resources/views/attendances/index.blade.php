<script src="https://cdn.tailwindcss.com"></script>
@extends('layouts.dashboard')

@section('content')

    <body class="bg-gray-100">
        <div class="min-h-screen flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-md bg-white/30 backdrop-blur-lg rounded-lg shadow-xl p-8">
                <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Form Absensi</h1>

                <!-- Form Absensi: Hanya pengambilan foto dan lokasi -->
                <form id="absensiForm" class="space-y-6">
                    <!-- Informasi identitas diambil dari session -->
                    <input type="hidden" name="role" value="{{ Auth::user()->role }}">
                    @if (Auth::user()->role === 'guru')
                        <input type="hidden" name="id" value="{{ Auth::user()->email }}">
                    @elseif(Auth::user()->role === 'siswa')
                        <!-- Jika user siswa, diasumsikan Anda memiliki relasi user ke model Siswa yang menyimpan nisn -->
                        <input type="hidden" name="id" value="{{ Auth::user()->siswa->nisn ?? '' }}">
                    @endif

                    <!-- Kamera dengan efek border kaca -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ambil Foto</label>
                        <div class="relative border rounded-md overflow-hidden bg-white/20 backdrop-blur-sm">
                            <video id="video" class="w-full h-auto"></video>
                            <canvas id="canvas" class="hidden"></canvas>
                            <img id="photoPreview" class="hidden w-full h-auto border rounded-md" alt="Foto Wajah">
                            <button id="takePhoto" type="button"
                                class="absolute bottom-2 right-2 bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded shadow">
                                Ambil Foto
                            </button>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Lokasi Anda" readonly required>
                    </div>

                    <!-- Tombol Absensi -->
                    <div class="flex justify-center">
                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow transition-colors duration-200">
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

                // Validasi: karena ID diambil otomatis, cukup validasi foto
                if (!photoData) {
                    Swal.fire('Gagal!', 'Anda harus mengambil foto.', 'error');
                    return;
                }

                // Kirim data ke server
                axios.post('{{ route('absensi.store') }}', {
                        role: document.querySelector('[name="role"]').value,
                        id: document.querySelector('[name="id"]').value,
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
@endsection
