<script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">

<div id="attendance-popup" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white w-2/3 rounded-lg p-4 relative">
        <button onclick="closeAttendancePopup()" class="absolute top-2 right-2 text-xl">✕</button>

        <h2 class="text-lg font-semibold mb-3">Detail Attendance</h2>

        <div class="space-y-2 mb-4">
            <p><strong>Nama:</strong> <span id="popup-name">{{ $item->user->name }}</span></p>
            <p><strong>Jam Masuk:</strong> <span id="popup-checkin">{{ $item->check_in->format('H:i') }}</span></p>
            <p><strong>Status Kehadiran:</strong>
                <span id="popup-status">
                    @foreach (['present' => 'Hadir', 'late' => 'Terlambat', 'sick' => 'Sakit', 'permission' => 'Izin', 'absent' => 'Alpa'] as $key => $value)
                        @if ($item->status === $key)
                            {{ $value }}
                        @endif
                    @endforeach
                </span>
            </p>
            <p><strong>Lokasi:</strong> <span id="popup-loc">{{ $item->latitude }}, {{ $item->longitude }}</span></p>
            <p><strong>Catatan:</strong> <span id="popup-note">{{ $item->note ?? '-' }}</span></p>
        </div>

        <div id="attendance-map" style="height: 400px; width: 100%; border-radius: 0.5rem;"></div>
    </div>
</div>

<script>
    let attendanceMap = null;
    let attendanceMarker = null;

    function openAttendancePopup(attendance) {
        // tampilkan popup
        const popup = document.getElementById('attendance-popup');
        popup.classList.remove('hidden');
        popup.classList.add('flex');


        // tampilkan map
        setTimeout(() => {
            const lat = parseFloat({{ $item->latitude }});
            const lng = parseFloat({{ $item->longitude }});

            if (!attendanceMap) {
                attendanceMap = L.map('attendance-map').setView([lat, lng], 19);

                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Tiles © Esri — Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
                        maxZoom: 19
                    }
                ).addTo(attendanceMap);

                attendanceMarker = L.marker([lat, lng]).addTo(attendanceMap);
            } else {
                attendanceMap.setView([lat, lng], 19);
                attendanceMarker.setLatLng([lat, lng]);
            }

            attendanceMap.invalidateSize(true);
        }, 300);
    }

    function closeAttendancePopup() {
        const popup = document.getElementById('attendance-popup');
        popup.classList.add('hidden');
        popup.classList.remove('flex');
    }
</script>
