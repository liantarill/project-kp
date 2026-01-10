<div>
    @if ($record->acceptance_proof)
        @php
            $file = asset('storage/' . $record->acceptance_proof);
            $ext = strtolower(pathinfo($record->acceptance_proof, PATHINFO_EXTENSION));
        @endphp

        <div class="section-container">
            <div class="alert-box">
                TEST FI ACTIVE
            </div>

            <div class="file-box">
                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                    <img src="{{ $file }}" alt="Acceptance Proof" class="file-image">
                @else
                    <iframe src="{{ $file }}" class="file-iframe"></iframe>
                @endif
            </div>
        </div>
    @else
        <div class="file-empty">
            Bukti tidak tersedia
        </div>
    @endif
</div>

<style>
    /* Container spacing */
    .section-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        /* jarak antar elemen */
    }

    /* Alert box */
    .alert-box {
        background-color: #ef4444;
        /* merah */
        color: white;
        padding: 1.5rem;
        border-radius: 1rem;
        font-weight: bold;
        text-align: center;
    }

    /* File display box */
    .file-box {
        background-color: white;
        border: 1px solid #e5e7eb;
        /* abu-abu */
        padding: 1rem;
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Image styling */
    .file-image {
        width: 100%;
        max-height: 500px;
        object-fit: contain;
        border-radius: 0.5rem;
    }

    /* Iframe styling */
    .file-iframe {
        width: 100%;
        height: 500px;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    /* Empty state */
    .file-empty {
        text-align: center;
        font-size: 0.875rem;
        /* kecil */
        color: #6b7280;
        /* abu-abu */
        font-style: italic;
    }
</style>
