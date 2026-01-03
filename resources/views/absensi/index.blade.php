<x-app-layout>
    <script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4">
        <div class="max-w-md mx-auto">

            <!-- Header Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Absensi Masuk</h1>
                        <p class="text-sm text-gray-500 mt-1">{{ now()->format('l, d F Y') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-clipboard-check text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 m-6">
                        <div class="flex">
                            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 mr-3"></i>
                            <ul class="text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 m-6">
                        <div class="flex">
                            <i class="fa-solid fa-circle-check text-green-500 mt-0.5 mr-3"></i>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('absensi.store') }}" enctype="multipart/form-data" class="p-6">
                    @csrf

                    <!-- Hidden Fields -->
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="status" id="statusInput" value="present">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <!-- Status Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Status Kehadiran</label>
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" onclick="selectStatus('present')"
                                class="status-btn bg-green-500 text-white py-3 rounded-lg font-medium hover:bg-green-600 transition-all transform hover:scale-105 shadow-sm"
                                data-status="present">
                                <i class="fa-solid fa-circle-check mb-1"></i>
                                <div class="text-sm">Hadir</div>
                            </button>
                            <button type="button" onclick="selectStatus('permission')"
                                class="status-btn bg-gray-200 text-gray-600 py-3 rounded-lg font-medium hover:bg-yellow-500 hover:text-white transition-all transform hover:scale-105 shadow-sm"
                                data-status="permission">
                                <i class="fa-solid fa-clipboard-list mb-1"></i>
                                <div class="text-sm">Izin</div>
                            </button>
                            <button type="button" onclick="selectStatus('sick')"
                                class="status-btn bg-gray-200 text-gray-600 py-3 rounded-lg font-medium hover:bg-blue-500 hover:text-white transition-all transform hover:scale-105 shadow-sm"
                                data-status="sick">
                                <i class="fa-solid fa-briefcase-medical mb-1"></i>
                                <div class="text-sm">Sakit</div>
                            </button>
                        </div>
                    </div>

                    <!-- Note Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                        <input type="text" name="note"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Tambahkan catatan (opsional)">
                    </div>

                    <!-- Camera Section -->
                    <div class="mb-6" id="cameraSection">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Foto Kehadiran</label>
                        <div class="bg-gray-50 rounded-lg p-4 border-2 border-dashed border-gray-300">
                            <video id="video" autoplay playsinline class="w-full rounded-lg shadow-sm"
                                style="transform: scaleX(-1);"></video>

                            <canvas id="canvas" class="hidden"></canvas>

                            <img id="previewImage" class="hidden w-full rounded-lg shadow-sm" />

                            <input type="hidden" name="photo" id="photo">

                            <div class="flex gap-3 mt-4">
                                <button type="button" onclick="takePhoto()" id="btnTakePhoto"
                                    class="flex-1 bg-blue-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-blue-700 transition shadow-sm">
                                    <i class="fa-solid fa-camera mr-2"></i>
                                    Ambil Foto
                                </button>

                                <button type="button" onclick="retakePhoto()" id="btnRetake"
                                    class="hidden flex-1 bg-yellow-500 text-white px-4 py-3 rounded-lg font-medium hover:bg-yellow-600 transition shadow-sm">
                                    <i class="fa-solid fa-arrows-rotate mr-2"></i>
                                    Ambil Ulang
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button (under camera for 'present' status) -->
                        <button type="submit" id="submitBtnCamera"
                            class="w-full bg-green-600 text-white py-4 rounded-lg font-semibold hover:bg-green-700 transition-all transform hover:scale-[1.02] shadow-md disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 mt-4"
                            disabled>
                            <i class="fa-solid fa-check-circle mr-2"></i>
                            Kirim Absensi
                        </button>
                    </div>

                    <!-- Location Status -->
                    <div class="mb-6" id="locationSection">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Lokasi Anda</label>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p id="locationStatus" class="text-sm text-gray-600 text-center">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                Mengambil lokasi...
                            </p>

                            <button type="button" onclick="requestLocation()" id="btnLocation"
                                class="hidden w-full bg-blue-600 text-white px-4 py-3 rounded-lg font-medium mt-3 hover:bg-blue-700 transition shadow-sm">
                                <i class="fa-solid fa-location-dot mr-2"></i>
                                Izinkan Akses Lokasi
                            </button>

                            <div id="attendance-map" class="hidden mt-4 rounded-lg overflow-hidden shadow-sm"
                                style="height: 200px; width: 100%;"></div>
                        </div>
                    </div>

                    <!-- Submit Button (for 'permission' and 'sick' status) -->
                    <button type="submit" id="submitBtnOther"
                        class="hidden w-full bg-green-600 text-white py-4 rounded-lg font-semibold hover:bg-green-700 transition-all transform hover:scale-[1.02] shadow-md disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        <i class="fa-solid fa-check-circle mr-2"></i>
                        Kirim Absensi
                    </button>
                </form>
            </div>

            <!-- Info Footer -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <i class="fa-solid fa-info-circle mr-1"></i>
                Pastikan lokasi dan foto sudah sesuai sebelum mengirim
            </div>
        </div>
    </div>

    <script>
        let attendanceMap = null;
        let attendanceMarker = null;
        let stream;
        let selectedStatus = 'present';

        document.addEventListener('DOMContentLoaded', () => {
            // Only request location if status is 'present' by default
            if (selectedStatus === 'present') {
                if (navigator.geolocation) {
                    requestLocation();
                } else {
                    handleNoGeolocation();
                }
            }

            // Request camera access for 'present' status
            if (selectedStatus === 'present') {
                initCamera();
            }
        });

        // Initialize camera
        function initCamera() {
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(s => {
                    stream = s;
                    document.getElementById('video').srcObject = stream;
                })
                .catch(() => {
                    alert('Kamera tidak dapat diakses. Pastikan izin kamera telah diberikan.');
                });
        }

        // Status selection
        function selectStatus(status) {
            selectedStatus = status;
            document.getElementById('statusInput').value = status;

            document.querySelectorAll('.status-btn').forEach(btn => {
                btn.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-blue-500', 'text-white', 'scale-105');
                btn.classList.add('bg-gray-200', 'text-gray-600');
            });

            const selectedBtn = document.querySelector(`[data-status="${status}"]`);
            selectedBtn.classList.remove('bg-gray-200', 'text-gray-600');
            selectedBtn.classList.add('scale-105');

            const cameraSection = document.getElementById('cameraSection');
            const locationSection = document.getElementById('locationSection');
            const submitBtnCamera = document.getElementById('submitBtnCamera');
            const submitBtnOther = document.getElementById('submitBtnOther');
            const photoInput = document.getElementById('photo');

            if (status === 'present') {
                selectedBtn.classList.add('bg-green-500', 'text-white');
                // Show camera and location section
                cameraSection.classList.remove('hidden');
                locationSection.classList.remove('hidden');
                submitBtnCamera.classList.remove('hidden');
                submitBtnOther.classList.add('hidden');
                // Disable camera submit button until photo is taken
                submitBtnCamera.disabled = !photoInput.value;
                // Restart camera and location if needed
                if (!stream || !stream.active) {
                    initCamera();
                }
                if (navigator.geolocation) {
                    requestLocation();
                }
            } else if (status === 'permission') {
                selectedBtn.classList.add('bg-yellow-500', 'text-white');
                // Hide camera and location section, show other submit button
                cameraSection.classList.add('hidden');
                locationSection.classList.add('hidden');
                submitBtnCamera.classList.add('hidden');
                submitBtnOther.classList.remove('hidden');
                submitBtnOther.disabled = false;
                // Stop camera
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                // Clear location data
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
            } else if (status === 'sick') {
                selectedBtn.classList.add('bg-blue-500', 'text-white');
                // Hide camera and location section, show other submit button
                cameraSection.classList.add('hidden');
                locationSection.classList.add('hidden');
                submitBtnCamera.classList.add('hidden');
                submitBtnOther.classList.remove('hidden');
                submitBtnOther.disabled = false;
                // Stop camera
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                // Clear location data
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
            }
        }

        // Take photo
        function takePhoto() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const photoInput = document.getElementById('photo');
            const preview = document.getElementById('previewImage');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/jpeg');
            photoInput.value = imageData;

            preview.src = imageData;
            preview.classList.remove('hidden');
            video.classList.add('hidden');

            document.getElementById('btnTakePhoto').classList.add('hidden');
            document.getElementById('btnRetake').classList.remove('hidden');

            stream.getTracks().forEach(track => track.stop());
            enableSubmitButton();
        }

        // Retake photo
        function retakePhoto() {
            const video = document.getElementById('video');
            const preview = document.getElementById('previewImage');
            const photoInput = document.getElementById('photo');

            photoInput.value = '';
            preview.classList.add('hidden');
            video.classList.remove('hidden');

            document.getElementById('btnTakePhoto').classList.remove('hidden');
            document.getElementById('btnRetake').classList.add('hidden');

            const submitBtnCamera = document.getElementById('submitBtnCamera');
            submitBtnCamera.disabled = true;

            initCamera();
        }

        // Enable submit
        function enableSubmitButton() {
            const photoInput = document.getElementById('photo');
            const submitBtnCamera = document.getElementById('submitBtnCamera');

            if (photoInput.value && selectedStatus === 'present') {
                submitBtnCamera.disabled = false;
            }
        }

        // Location functions
        const OFFICE_LAT = -5.3851721;
        const OFFICE_LNG = 105.2605921;
        const MAX_RADIUS = 100;

        function getDistanceFromLatLonInMeters(lat1, lon1, lat2, lon2) {
            const R = 6371000;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;

            const a =
                Math.sin(dLat / 2) ** 2 +
                Math.cos(lat1 * Math.PI / 180) *
                Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) ** 2;

            return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
        }

        function requestLocation() {
            const statusEl = document.getElementById('locationStatus');
            statusEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Mengambil lokasi...';

            navigator.geolocation.getCurrentPosition(
                handleLocationSuccess,
                handleLocationError, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        function handleLocationSuccess(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            document.getElementById('latitude').value = userLat;
            document.getElementById('longitude').value = userLng;

            const distance = getDistanceFromLatLonInMeters(OFFICE_LAT, OFFICE_LNG, userLat, userLng);
            const statusEl = document.getElementById('locationStatus');
            const mapEl = document.getElementById('attendance-map');

            if (distance <= MAX_RADIUS) {
                statusEl.innerHTML = `
                    <div class="flex items-center justify-center text-green-600">
                        <i class="fa-solid fa-circle-check mr-2"></i>
                        <span class="font-semibold">Dalam area kantor</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Jarak: ${Math.round(distance)} meter dari kantor</div>
                `;
            } else {
                statusEl.innerHTML = `
                    <div class="flex items-center justify-center text-red-600">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                        <span class="font-semibold">Di luar area kantor</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Jarak: ${Math.round(distance)} meter dari kantor</div>
                `;
            }

            document.getElementById('btnLocation').classList.add('hidden');
            mapEl.classList.remove('hidden');
            showAttendanceMap(userLat, userLng);
        }

        function handleLocationError(error) {
            const statusEl = document.getElementById('locationStatus');
            const btnLocation = document.getElementById('btnLocation');

            let message = '';
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    message =
                        '<i class="fa-solid fa-circle-xmark mr-2"></i><b>Akses lokasi ditolak</b><br><span class="text-xs">Aktifkan izin lokasi di pengaturan browser</span>';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = '<i class="fa-solid fa-circle-xmark mr-2"></i><b>Lokasi tidak tersedia</b>';
                    break;
                case error.TIMEOUT:
                    message = '<i class="fa-solid fa-circle-xmark mr-2"></i><b>Waktu habis mengambil lokasi</b>';
                    break;
                default:
                    message = '<i class="fa-solid fa-circle-xmark mr-2"></i><b>Error mengambil lokasi</b>';
            }

            statusEl.innerHTML = `<div class="text-red-600">${message}</div>`;
            btnLocation.classList.remove('hidden');
        }

        function handleNoGeolocation() {
            document.getElementById('locationStatus').innerHTML =
                '<div class="text-red-600"><i class="fa-solid fa-circle-xmark mr-2"></i><b>Browser tidak mendukung GPS</b></div>';
        }

        function showAttendanceMap(lat, lng) {
            if (!attendanceMap) {
                attendanceMap = L.map('attendance-map', {
                    dragging: false,
                    scrollWheelZoom: false,
                    doubleClickZoom: false,
                    boxZoom: false,
                    keyboard: false,
                    touchZoom: false,
                    zoomControl: false
                }).setView([lat, lng], 17);

                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        maxZoom: 19
                    }
                ).addTo(attendanceMap);

                attendanceMarker = L.marker([lat, lng]).addTo(attendanceMap);
            } else {
                attendanceMap.setView([lat, lng], 17);
                attendanceMarker.setLatLng([lat, lng]);
            }

            setTimeout(() => attendanceMap.invalidateSize(), 100);
        }
    </script>
</x-app-layout>
