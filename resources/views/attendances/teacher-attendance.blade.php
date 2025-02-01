@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Absensi Guru</h1>
        <form id="teacherAttendanceForm">
            <!-- Field tersembunyi untuk GPS dan Selfie -->
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <input type="hidden" name="selfie" id="selfie">

            <!-- Pilihan Status Absensi -->
            <div class="mb-4">
                <label for="status" class="block font-semibold mb-1">Status Absensi</label>
                <select id="status" name="status"
                    class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-gray-100">
                    <option value="Hadir">Hadir</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                    <option value="Alfa">Alfa</option>
                </select>
            </div>

            <!-- Tombol Verifikasi Lokasi dan Capture Selfie -->
            <div class="mb-4">
                <button type="button" onclick="verifyLocation()"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Verify Location & Capture Selfie
                </button>
            </div>

            <!-- Bagian Kamera (hidden initially) -->
            <div id="cameraSection" class="mt-4 hidden">
                <div class="mb-2">
                    <video id="video" width="320" height="240" autoplay class="border rounded"></video>
                </div>
                <button type="button" onclick="captureSelfie()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Capture Selfie
                </button>
                <canvas id="canvas" width="320" height="240" class="hidden"></canvas>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mt-4">
                Submit Attendance
            </button>
        </form>
    </div>

    <div id="notification" class="hidden fixed top-5 right-5 p-4 bg-blue-600 text-white rounded shadow">
        <span id="notificationMessage"></span>
    </div>

    <script>
        // Koordinat sekolah dan radius (dalam meter)
        const schoolLatitude = -6.200000; // Ganti dengan latitude sekolah Anda
        const schoolLongitude = 106.816666; // Ganti dengan longitude sekolah Anda
        const allowedRadius = 100; // Contoh: 100 meter

        function getDistanceFromLatLonInMeters(lat1, lon1, lat2, lon2) {
            const R = 6371000;
            const dLat = deg2rad(lat2 - lat1);
            const dLon = deg2rad(lon2 - lon1);
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function deg2rad(deg) {
            return deg * (Math.PI / 180);
        }

        function verifyLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const userLat = position.coords.latitude;
                    const userLon = position.coords.longitude;
                    document.getElementById('latitude').value = userLat;
                    document.getElementById('longitude').value = userLon;

                    const distance = getDistanceFromLatLonInMeters(userLat, userLon, schoolLatitude,
                        schoolLongitude);
                    if (distance <= allowedRadius) {
                        alert('Location verified! You are within the allowed area.');
                        document.getElementById('cameraSection').classList.remove('hidden');
                        startCamera();
                    } else {
                        alert('You are not within the allowed area. Distance: ' + Math.round(distance) + ' meters');
                    }
                }, function(error) {
                    alert('Error getting location: ' + error.message);
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }

        function startCamera() {
            const video = document.getElementById('video');
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({
                        video: true
                    })
                    .then(function(stream) {
                        video.srcObject = stream;
                        video.play();
                    })
                    .catch(function(error) {
                        alert('Error accessing camera: ' + error.message);
                    });
            }
        }

        function captureSelfie() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataURL = canvas.toDataURL('image/png');
            document.getElementById('selfie').value = dataURL;
            alert('Selfie captured successfully.');
        }

        $('#teacherAttendanceForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('attendance.mark') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showNotification('Attendance submitted successfully', 'success');
                },
                error: function(xhr) {
                    alert('Error submitting attendance');
                }
            });
        });

        function showNotification(message, type = 'success') {
            const notification = $('#notification');
            $('#notificationMessage').text(message);
            notification.removeClass('hidden');
            setTimeout(() => {
                notification.addClass('hidden');
            }, 3000);
        }
    </script>
@endsection
