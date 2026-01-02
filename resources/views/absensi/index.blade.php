<x-app-layout>

    <div class="min-h-screen flex items-center justify-center">

        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
            @if ($errors->any())
                <div class="text-red-500">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h1 class="text-xl font-semibold text-center mb-4">
                Absensi Masuk
            </h1>

            @if (session('success'))
                <div class="mb-3 text-green-600 text-sm text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('absensi.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Hidden fields --}}
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <input type="hidden" name="status" id="statusInput" value="present">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                {{-- Pilihan Status Absensi --}}
                <div class="mb-4">
                    <label class="block text-sm mb-2 font-medium">Pilih Status</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="selectStatus('present')"
                            class="status-btn bg-green-500 text-white py-2 rounded text-sm font-medium hover:bg-green-600 transition"
                            data-status="present">
                            Hadir
                        </button>
                        <button type="button" onclick="selectStatus('permission')"
                            class="status-btn bg-gray-300 text-gray-700 py-2 rounded text-sm font-medium hover:bg-gray-400 transition"
                            data-status="permission">
                            Izin
                        </button>
                        <button type="button" onclick="selectStatus('sick')"
                            class="status-btn bg-gray-300 text-gray-700 py-2 rounded text-sm font-medium hover:bg-gray-400 transition"
                            data-status="sick">
                            Sakit
                        </button>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="mb-3">
                    <label class="block text-sm mb-1">Catatan</label>
                    <input type="text" name="note" class="w-full border rounded px-3 py-2 text-sm"
                        placeholder="Contoh: Hadir tepat waktu">
                </div>

                {{-- Kamera --}}
                <div class="mb-3 text-center">
                    <video id="video" autoplay playsinline class="w-full rounded"
                        style="transform: scaleX(-1);"></video>

                    <canvas id="canvas" class="hidden"></canvas>

                    <img id="previewImage" class="hidden w-full rounded mt-2" />

                    <input type="hidden" name="photo" id="photo">

                    <button type="button" onclick="takePhoto()" id="btnTakePhoto"
                        class="bg-blue-600 text-white px-4 py-2 rounded text-sm mt-2 hover:bg-blue-700 transition">
                        📷 Ambil Foto
                    </button>

                    <button type="button" onclick="retakePhoto()" id="btnRetake"
                        class="hidden bg-yellow-600 text-white px-4 py-2 rounded text-sm mt-2 hover:bg-yellow-700 transition">
                        🔄 Ambil Ulang
                    </button>

                </div>

                <p id="locationStatus" class="text-xs text-center text-gray-500 mb-3">
                    Mengambil lokasi...
                </p>

                <button type="button" onclick="requestLocation()" id="btnLocation"
                    class="hidden w-full bg-blue-600 text-white px-4 py-2 rounded text-sm mb-3 hover:bg-blue-700 transition">
                    📍 Izinkan Akses Lokasi
                </button>

                <button type="submit" id="submitBtn" disabled
                    class="w-full bg-green-600 text-white py-2 rounded opacity-50 text-sm hover:bg-green-700 transition">
                    Absen Sekarang
                </button>
            </form>
        </div>

        <script>
            let stream;
            let selectedStatus = 'present';

            // Fungsi pilih status
            function selectStatus(status) {
                selectedStatus = status;
                document.getElementById('statusInput').value = status;

                // Reset semua button
                document.querySelectorAll('.status-btn').forEach(btn => {
                    btn.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-blue-500', 'text-white');
                    btn.classList.add('bg-gray-300', 'text-gray-700');
                });

                // Highlight button yang dipilih
                const selectedBtn = document.querySelector(`[data-status="${status}"]`);
                selectedBtn.classList.remove('bg-gray-300', 'text-gray-700');

                if (status === 'present') {
                    selectedBtn.classList.add('bg-green-500', 'text-white');
                } else if (status === 'permission') {
                    selectedBtn.classList.add('bg-yellow-500', 'text-white');
                } else if (status === 'sick') {
                    selectedBtn.classList.add('bg-blue-500', 'text-white');
                }
            }

            // Akses kamera
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(s => {
                    stream = s;
                    document.getElementById('video').srcObject = stream;
                })
                .catch(() => alert('Kamera tidak dapat diakses'));

            // Ambil foto
            function takePhoto() {
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const photoInput = document.getElementById('photo');
                const preview = document.getElementById('previewImage');

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                const ctx = canvas.getContext('2d');

                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Hasil foto
                const imageData = canvas.toDataURL('image/jpeg');

                // Simpan ke input hidden
                photoInput.value = imageData;

                // Tampilkan gambar
                preview.src = imageData;
                preview.classList.remove('hidden');

                // Sembunyikan video
                video.classList.add('hidden');

                // Sembunyikan button ambil foto, tampilkan button ambil ulang
                document.getElementById('btnTakePhoto').classList.add('hidden');
                document.getElementById('btnRetake').classList.remove('hidden');

                // Matikan kamera
                stream.getTracks().forEach(track => track.stop());

                // Aktifkan submit
                enableSubmitButton();
            }

            // Ambil ulang foto
            function retakePhoto() {
                const video = document.getElementById('video');
                const preview = document.getElementById('previewImage');
                const photoInput = document.getElementById('photo');

                // Reset foto
                photoInput.value = '';
                preview.classList.add('hidden');
                video.classList.remove('hidden');

                // Toggle buttons
                document.getElementById('btnTakePhoto').classList.remove('hidden');
                document.getElementById('btnRetake').classList.add('hidden');

                // Disable submit
                const btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.classList.add('opacity-50');

                // Restart kamera
                navigator.mediaDevices.getUserMedia({
                        video: true
                    })
                    .then(s => {
                        stream = s;
                        video.srcObject = stream;
                    })
                    .catch(() => alert('Kamera tidak dapat diakses'));
            }

            // Enable submit button
            function enableSubmitButton() {
                const btn = document.getElementById('submitBtn');
                const photoInput = document.getElementById('photo');

                if (photoInput.value) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50');
                }
            }

            // Ambil GPS
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

            // Function untuk request lokasi
            function requestLocation() {
                document.getElementById('locationStatus').innerText = 'Meminta akses lokasi...';
                document.getElementById('locationStatus').style.color = 'gray';

                navigator.geolocation.getCurrentPosition(
                    handleLocationSuccess,
                    handleLocationError, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            }

            // Success handler
            function handleLocationSuccess(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                document.getElementById('latitude').value = userLat;
                document.getElementById('longitude').value = userLng;

                const distance = getDistanceFromLatLonInMeters(
                    OFFICE_LAT,
                    OFFICE_LNG,
                    userLat,
                    userLng
                );

                const statusEl = document.getElementById('locationStatus');

                if (distance <= MAX_RADIUS) {
                    statusEl.innerHTML =
                        `✅ <b>Dalam area kantor</b><br>Jarak: ${Math.round(distance)} meter`;
                    statusEl.style.color = 'green';
                } else {
                    statusEl.innerHTML =
                        `⚠️ <b>Di luar area kantor</b><br>Jarak: ${Math.round(distance)} meter`;
                    statusEl.style.color = 'red';
                }

                // Sembunyikan button location setelah berhasil
                document.getElementById('btnLocation').classList.add('hidden');
            }

            // Error handler
            function handleLocationError(error) {
                const statusEl = document.getElementById('locationStatus');

                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        statusEl.innerHTML =
                            '❌ <b>Akses lokasi ditolak!</b><br>' +
                            '<span class="text-xs">Aktifkan di Settings Safari → Privacy & Security → Location</span>';
                        // Tampilkan button untuk retry
                        document.getElementById('btnLocation').classList.remove('hidden');
                        break;
                    case error.POSITION_UNAVAILABLE:
                        statusEl.innerHTML = '❌ <b>Lokasi tidak tersedia</b><br>' +
                            '<span class="text-xs">Coba lagi</span>';
                        document.getElementById('btnLocation').classList.remove('hidden');
                        break;
                    case error.TIMEOUT:
                        statusEl.innerHTML = '❌ <b>Timeout mengambil lokasi</b><br>' +
                            '<span class="text-xs">Coba lagi</span>';
                        document.getElementById('btnLocation').classList.remove('hidden');
                        break;
                    default:
                        statusEl.innerHTML = '❌ <b>Error mengambil lokasi</b>';
                        document.getElementById('btnLocation').classList.remove('hidden');
                }
                statusEl.style.color = 'red';
            }

            // Cek apakah geolocation tersedia dan langsung request
            if (!navigator.geolocation) {
                document.getElementById('locationStatus').innerHTML =
                    '❌ <b>Browser tidak mendukung GPS</b>';
                document.getElementById('locationStatus').style.color = 'red';
            } else {
                // Langsung minta akses lokasi saat page load
                requestLocation();
            }
        </script>

    </div>

</x-app-layout>
