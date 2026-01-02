<script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">
<div id="attendance-popup" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white w-2/3 rounded-lg p-4 relative">
        <button onclick="closeAttendancePopup()" class="absolute top-2 right-2 text-xl">✕</button>

        <h2 class="text-lg font-semibold mb-3">Detail Attendance</h2>

        <div class="space-y-1 mb-4">
            <p><strong>Tanggal:</strong> <span id="popup-date"></span></p>
            <p><strong>Nama:</strong> <span id="popup-name"></span></p>
            <p><strong>Lokasi:</strong>
                <span id="popup-lat"></span>,
                <span id="popup-lng"></span>
            </p>
        </div>

        <div id="attendance-map" style="height: 400px; width: 100%; border-radius: 0.5rem;"></div>
    </div>
</div>



<script>
    let attendanceMap = null;
    let attendanceMarker = null;

    function openAttendancePopup(attendance) {

        document.getElementById('attendance-popup').classList.remove('hidden');
        document.getElementById('attendance-popup').classList.add('flex');

        document.getElementById('popup-date').innerText = attendance['date'];
        document.getElementById('popup-name').innerText = attendance['user']['name'];
        document.getElementById('popup-lat').innerText = attendance['latitude'];
        document.getElementById('popup-lng').innerText = attendance['longitude'];

        setTimeout(() => {
            if (!attendanceMap) {
                attendanceMap = L.map('attendance-map').setView([attendance['latitude'], attendance[
                    'longitude']], 19);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(attendanceMap);
            } else {
                attendanceMap.setView([attendance['latitude'], attendance['longitude']], 19);
            }

            if (attendanceMarker) {
                attendanceMarker.setLatLng([attendance['latitude'], attendance['longitude']]);
            } else {
                attendanceMarker = L.marker([attendance['latitude'], attendance['longitude']]).addTo(
                    attendanceMap);
            }

            attendanceMap.invalidateSize(true);
        }, 300);
    }

    function closeAttendancePopup() {
        document.getElementById('attendance-popup').classList.add('hidden');
        document.getElementById('attendance-popup').classList.remove('flex');
    }
</script>
