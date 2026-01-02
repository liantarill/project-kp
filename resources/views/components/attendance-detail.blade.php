<script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">

<div id="attendance-map" style="height: 400px; width: 100%; border-radius: 0.5rem;"></div>


<script>
    let attendanceMap = null;
    let attendanceMarker = null;

    function openAttendancePopup(attendance) {
        document.getElementById('attendance-popup').classList.remove('hidden');
        document.getElementById('attendance-popup').classList.add('flex');

        document.getElementById('popup-date').innerText = attendance['date'];
        document.getElementById('popup-name').innerText = attendance['name'];
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
