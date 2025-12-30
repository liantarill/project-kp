{{-- resources/views/filament/partials/attendance-detail.blade.php --}}

<div x-data="{
    mapId: 'attendance-map-{{ $record->id }}',
    lat: {{ $record->latitude ?? 0 }},
    lng: {{ $record->longitude ?? 0 }},
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
            L.marker([self.lat, self.lng]).addTo(map).bindPopup('{{ $record->user->name }}').openPopup();

            setTimeout(() => map.invalidateSize(), 300);
        }, 400);
    }
}" x-init="initMap()">
    <div class="space-y-2 mb-4">
        <p><strong>Tanggal:</strong> {{ $record->date }}</p>
        <p><strong>Nama:</strong> {{ $record->user->name }}</p>
        <p><strong>Lokasi:</strong> {{ $record->latitude }}, {{ $record->longitude }}</p>
    </div>

    <div :id="mapId" style="height: 400px; width: 100%; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
    </div>
</div>
