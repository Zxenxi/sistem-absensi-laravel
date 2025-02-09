<script src="https://cdn.tailwindcss.com"></script>
@extends('layouts.dashboard')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Form Absensi</title>
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
        <div class="min-h-screen flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-md bg-white/30 backdrop-blur-lg rounded-lg shadow-xl p-8">
                <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Form Absensi</h1>
                <form id="absensiForm" class="space-y-6">
                    <!-- Identitas Pengguna -->
                    <input type="hidden" name="role" value="{{ Auth::user()->role }}">
                    @if (Auth::user()->role === 'guru')
                        <input type="hidden" name="id" value="{{ Auth::user()->email }}">
                    @elseif(Auth::user()->role === 'siswa')
                        <!-- Karena data siswa disimpan di tabel users, gunakan data dari Auth::user() -->
                        <input type="hidden" name="id" value="{{ Auth::user()->nisn }}">
                    @endif

                    <!-- Input lokasi tersembunyi -->
                    <input type="hidden" id="lokasi" name="lokasi">

                    <!-- Bagian Kamera & Foto -->
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

                    <!-- Bagian Status Lokasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Lokasi</label>
                        <div id="locationContainer" class="flex items-center mt-1 space-x-2">
                            <svg id="locationSpinner" class="animate-spin h-5 w-5 text-blue-500"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span id="locationStatus" class="text-xs text-gray-500">Mendapatkan lokasi...</span>
                        </div>
                    </div>

                    <!-- Tombol Absensi -->
                    <div class="flex justify-center">
                        <button type="submit" id="submitBtn"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow transition-colors duration-200"
                            disabled>Absen</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Script JavaScript -->
        <script>
            // Pengaturan kamera menggunakan MediaDevices API
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
                photoData = canvas.toDataURL('image/png');
                photoPreview.src = photoData;
                photoPreview.classList.remove('hidden');
                video.classList.add('hidden');
                takePhotoButton.classList.add('hidden');
            });

            // Pengaturan Geolocation untuk mendapatkan lokasi pengguna
            const lokasiInput = document.getElementById('lokasi');
            const locationStatus = document.getElementById('locationStatus');
            const locationSpinner = document.getElementById('locationSpinner');
            const submitBtn = document.getElementById('submitBtn');

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            lokasiInput.value = `${lat}, ${lng}`;
                            locationStatus.textContent = 'Lokasi berhasil didapatkan.';
                            locationSpinner.style.display = 'none';
                            submitBtn.disabled = false;
                        },
                        function(error) {
                            let errorMsg = 'Gagal mendapatkan lokasi. Pastikan GPS aktif.';
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMsg =
                                        'Izin akses lokasi ditolak. Silakan aktifkan izin lokasi di pengaturan browser.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMsg = 'Informasi lokasi tidak tersedia.';
                                    break;
                                case error.TIMEOUT:
                                    errorMsg = 'Waktu pengambilan lokasi habis. Silakan coba lagi.';
                                    break;
                            }
                            locationStatus.textContent = errorMsg;
                            locationSpinner.style.display = 'none';
                            submitBtn.disabled = true;
                            Swal.fire('Gagal!', errorMsg, 'error');
                        }
                    );
                } else {
                    const msg = 'Browser Anda tidak mendukung GPS.';
                    locationStatus.textContent = msg;
                    locationSpinner.style.display = 'none';
                    submitBtn.disabled = true;
                    Swal.fire('Gagal!', msg, 'error');
                }
            }
            getLocation();

            // Proses submit form absensi menggunakan axios
            document.getElementById('absensiForm').addEventListener('submit', function(e) {
                e.preventDefault();
                if (!photoData) {
                    Swal.fire('Gagal!', 'Anda harus mengambil foto terlebih dahulu.', 'error');
                    return;
                }
                axios.post('{{ route('absensi.store') }}', {
                        role: document.querySelector('[name="role"]').value,
                        id: document.querySelector('[name="id"]').value,
                        lokasi: lokasiInput.value,
                        foto_wajah: photoData,
                    })
                    .then(response => {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        document.getElementById('absensiForm').reset();
                        photoPreview.classList.add('hidden');
                        video.classList.remove('hidden');
                        takePhotoButton.classList.remove('hidden');
                        submitBtn.disabled = true;
                        lokasiInput.value = '';
                        locationStatus.textContent = 'Mendapatkan lokasi...';
                        locationSpinner.style.display = 'block';
                        getLocation();
                    })
                    .catch(error => {
                        Swal.fire('Gagal!', error.response.data.message || 'Terjadi kesalahan.', 'error');
                    });
            });
        </script>
    </body>

    </html>
@endsection
