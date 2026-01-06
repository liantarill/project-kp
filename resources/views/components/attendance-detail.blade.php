<script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">

<!-- Attendance Detail Popup -->
<div id="attendance-popup" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl relative" id="popup-content">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Detail Absensi</h2>
                <p class="text-xs text-slate-400 mt-0.5">Informasi lengkap kehadiran</p>
            </div>
            <button onclick="closeAttendancePopup()"
                class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-slate-100 transition-colors text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <!-- Content -->
        <div class="px-6 py-5 space-y-4 max-h-[70vh] overflow-y-auto">

            <!-- User Info Card -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-4 border border-blue-100/50">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-blue-600 text-[26px]">person</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 font-medium">Nama Pegawai</p>
                        <p class="text-sm font-semibold text-slate-900" id="popup-name">-</p>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-2 gap-3">
                <!-- Check In Time -->
                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-100">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="material-symbols-outlined text-slate-400 text-[18px]">schedule</span>
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Jam Masuk</p>
                    </div>
                    <p class="text-sm font-bold text-slate-900" id="popup-checkin">--:--</p>
                </div>

                <!-- Status -->
                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-100">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="material-symbols-outlined text-slate-400 text-[18px]">info</span>
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Status</p>
                    </div>
                    <span id="popup-status"
                        class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wide border border-emerald-100/50">
                        Hadir
                    </span>
                </div>
            </div>

            <!-- Location Info -->
            <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-100">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-slate-400 text-[18px]">location_on</span>
                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Koordinat Lokasi</p>
                </div>
                <p class="text-xs text-slate-600 bg-white px-3 py-2 rounded-lg border border-slate-200" id="popup-loc">-
                </p>
            </div>

            <!-- Notes -->
            <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-100">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-slate-400 text-[18px]">note</span>
                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Catatan</p>
                </div>
                <p class="text-sm text-slate-600" id="popup-note">-</p>
            </div>

            <!-- Map Container -->
            <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
                <div class="bg-slate-100 px-4 py-2.5 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-500 text-[18px]">map</span>
                        <p class="text-xs text-slate-600 font-semibold">Peta Lokasi</p>
                    </div>
                </div>
                <div id="attendance-map" class="w-full h-72 bg-slate-100"></div>
            </div>

        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-100">
            <button onclick="closeAttendancePopup()"
                class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition-all flex items-center justify-center gap-2">
                <span>Tutup</span>
                <span class="material-symbols-outlined text-[18px]">check</span>
            </button>
        </div>

    </div>
</div>

<script>
    let attendanceMap = null;
    let attendanceMarker = null;

    function openAttendancePopup(attendance) {
        console.log('Opening popup with data:', attendance);

        const popup = document.getElementById('attendance-popup');

        popup.classList.remove('hidden');
        popup.classList.add('flex');

        // Populate data
        document.getElementById('popup-name').textContent = attendance.user?.name || '-';
        document.getElementById('popup-checkin').textContent = attendance.check_in ?
            new Date(attendance.check_in).toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            }) : '--:--';
        document.getElementById('popup-loc').textContent = `${attendance.latitude}, ${attendance.longitude}`;
        document.getElementById('popup-note').textContent = attendance.note || 'Tidak ada catatan';

        // Update status badge
        const statusElement = document.getElementById('popup-status');
        const statusMap = {
            'present': {
                text: 'Hadir',
                class: 'bg-emerald-50 text-emerald-700 border-emerald-100/50'
            },
            'late': {
                text: 'Terlambat',
                class: 'bg-yellow-50 text-yellow-700 border-yellow-100/50'
            },
            'sick': {
                text: 'Sakit',
                class: 'bg-rose-50 text-rose-700 border-rose-100/50'
            },
            'permission': {
                text: 'Izin',
                class: 'bg-blue-50 text-blue-700 border-blue-100/50'
            },
            'absent': {
                text: 'Alfa',
                class: 'bg-slate-50 text-slate-700 border-slate-100/50'
            }
        };

        const status = statusMap[attendance.status] || statusMap['present'];
        statusElement.textContent = status.text;
        statusElement.className =
            `inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide border ${status.class}`;

        // Initialize or update map
        setTimeout(() => {
            const lat = parseFloat(attendance.latitude);
            const lng = parseFloat(attendance.longitude);

            if (!attendanceMap) {
                attendanceMap = L.map('attendance-map').setView([lat, lng], 18);

                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Tiles © Esri',
                        maxZoom: 19
                    }
                ).addTo(attendanceMap);

                attendanceMarker = L.marker([lat, lng]).addTo(attendanceMap);
            } else {
                attendanceMap.setView([lat, lng], 18);
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

    // Close on outside click
    document.getElementById('attendance-popup')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAttendancePopup();
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const popup = document.getElementById('attendance-popup');
            if (!popup.classList.contains('hidden')) {
                closeAttendancePopup();
            }
        }
    });
</script>
