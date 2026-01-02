<x-app-layout>
    <script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">

    <div x-data="{
        mapId: 'attendance-map-{{ $attendance->id }}',
        lat: {{ $attendance->latitude ?? 0 }},
        lng: {{ $attendance->longitude ?? 0 }},
        initMap() {
            const self = this;
            setTimeout(() => {
                if (typeof L === 'undefined') return;
    
                const container = document.getElementById(self.mapId);
                if (!container) return;
    
                if (container._leaflet_id) {
                    container._leaflet_id = null;
                    container.innerHTML = '';
                }
    
                const map = L.map(self.mapId).setView([self.lat, self.lng], 19);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                L.marker([self.lat, self.lng]).addTo(map).bindPopup('{{ $attendance->user_name }}').openPopup();
    
                setTimeout(() => map.invalidateSize(), 300);
            }, 400);
        }
    }" x-init="initMap()">
        <div class="space-y-2 mb-4">
            <p><strong>Tanggal:</strong> {{ $attendance->date }}</p>
            <p><strong>Nama:</strong> {{ $attendance->user->name }}</p>
            <p><strong>Lokasi:</strong> {{ $attendance->latitude }}, {{ $attendance->longitude }}</p>
        </div>

        <div :id="mapId"
            style="height: 400px; width: 100%; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
        </div>
    </div>
</x-app-layout>
