<style>
    /* Container utama */
    .attendance-container {
        max-width: 1120px;
        margin: 0 auto;
        padding: 24px 12px 48px 12px;
    }


    /* Header bulan */
    .attendance-month {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #94a3b8;
        margin: 32px 0 12px 0;
    }

    /* Grid galeri */
    .attendance-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        align-items: start;
        grid-auto-rows: min-content;
    }


    /* Kotak foto */
    .attendance-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
    }

    .attendance-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Foto */
    .attendance-card img {
        width: 100%;
        /* height: 204px; */
        object-fit: cover;
        display: block;
    }

    /* Tanggal di bawah foto */
    .attendance-date {
        padding: 6px 8px;
        font-size: 10px;
        color: #64748b;
    }

    /* Pesan kosong */
    .attendance-empty {
        text-align: center;
        color: #94a3b8;
        padding: 64px 0;
    }

    /* Responsif untuk layar kecil */
    /* @media (max-width: 640px) {
        .attendance-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 8px;
        }

        .attendance-card img {
            height: 120px;
        }
    } */

    /* Small screen ≥ 640px -> 3 kolom */
    @media (min-width: 640px) {
        .attendance-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
    }

    /* Medium screen ≥ 768px -> 4 kolom */
    @media (min-width: 768px) {
        .attendance-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Large screen ≥ 1024px -> 5 kolom */
    @media (min-width: 1024px) {
        .attendance-grid {
            grid-template-columns: repeat(5, 1fr);
        }
    }
</style>

<div class="attendance-container">


    @php
        $userPhotos = $attendances->where('user_id', $user->id);
        $photosGrouped = $userPhotos->groupBy(function ($p) {
            return \Carbon\Carbon::parse($p->date)->translatedFormat('F Y');
        });
    @endphp

    @forelse ($photosGrouped as $month => $items)

        <!-- Header Bulan -->
        <div class="attendance-month">{{ $month }}</div>

        <!-- Grid Galeri -->
        <div class="attendance-grid">
            @foreach ($items as $attendance)
                @if ($attendance->photo)
                    <div class="attendance-card">
                        <img src="{{ asset('storage/' . $attendance->photo) }}" loading="lazy" alt="Foto Absensi">
                        <div class="attendance-date">
                            {{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') }}
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

    @empty
        <div class="attendance-empty">Belum ada foto absensi</div>
    @endforelse

</div>
