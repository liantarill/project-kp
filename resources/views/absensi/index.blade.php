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
                <input type="hidden" name="date" value="{{ now()->toDateString() }}">
                <input type="hidden" name="check_in" value="{{ now()->format('H:i:s') }}">
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <input type="hidden" name="status" value="present">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                {{-- Catatan --}}
                <div class="mb-3">
                    <label class="block text-sm mb-1">Catatan</label>
                    <input type="text" name="note" class="w-full border rounded px-3 py-2 text-sm"
                        placeholder="Contoh: Hadir tepat waktu">
                </div>

                {{-- Kamera --}}
                <div class="mb-3 text-center">
                    <video id="video" autoplay class="rounded border w-full"></video>

                    <canvas id="canvas" class="hidden"></canvas>

                    <input type="hidden" name="photo" id="photo">

                    <button type="button" onclick="takePhoto()"
                        class="mt-2 w-full bg-blue-500 text-white py-2 rounded text-sm">
                        Ambil Foto
                    </button>
                </div>

                <p id="locationStatus" class="text-xs text-center text-gray-500 mb-3">
                    Mengambil lokasi...
                </p>

                <button type="submit" id="submitBtn" disabled
                    class="w-full bg-green-600 text-white py-2 rounded opacity-50 text-sm">
                    Absen Sekarang
                </button>
            </form>
        </div>

        <script>
            // Aktifkan kamera
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(stream => {
                    document.getElementById('video').srcObject = stream;
                })
                .catch(() => alert('Kamera tidak dapat diakses'));

            function takePhoto() {
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const photo = document.getElementById('photo');

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0);

                photo.value = canvas.toDataURL('image/jpeg');

                document.getElementById('submitBtn').disabled = false;
                document.getElementById('submitBtn').classList.remove('opacity-50');
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

            navigator.geolocation.getCurrentPosition(position => {
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
                const btn = document.getElementById('btnAbsen');

                if (distance <= MAX_RADIUS) {
                    statusEl.innerHTML =
                        `✅ <b>Dalam area kantor</b><br>Jarak: ${Math.round(distance)} meter`;
                    statusEl.style.color = 'green';
                    btn.disabled = false;
                } else {
                    statusEl.innerHTML =
                        `⚠️ <b>Di luar area kantor</b><br>Jarak: ${Math.round(distance)} meter`;
                    statusEl.style.color = 'red';
                    btn.disabled = true;
                }

            }, () => {
                document.getElementById('locationStatus').innerText =
                    '❌ Gagal mengambil lokasi';
                document.getElementById('btnAbsen').disabled = true;
            }, {
                enableHighAccuracy: true
            });
        </script>

    </div>

</x-app-layout>
