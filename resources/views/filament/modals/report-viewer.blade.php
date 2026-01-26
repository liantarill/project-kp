<div class="pdf-wrapper">
    @if ($record->report_file)
        @php
            $file = asset('storage/' . $record->report_file);
            $ext = strtolower(pathinfo($record->report_file, PATHINFO_EXTENSION));
        @endphp

        @if ($ext === 'pdf')
            <div class="section-container">
                <div class="file-box">
                    <iframe src="{{ $file }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH&zoom=150"
                        class="pdf-viewer" loading="lazy"></iframe>
                </div>
            </div>
        @else
            <div class="file-empty">
                File bukan PDF
            </div>
        @endif
    @else
        <div class="file-empty">
            Laporan tidak tersedia
        </div>
    @endif
</div>

<style>
    /* FULL WIDTH container (penting untuk modal Filament) */
    .pdf-wrapper {
        width: 100%;
        max-width: 100vw;
    }

    /* Stack layout */
    .section-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Hilangkan padding agar PDF full */
    .file-box {
        width: 100%;
        padding: 0;
        border-radius: 1rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* PDF viewer super lebar */
    .pdf-viewer {
        width: 100%;
        height: 80vh;
        /* bikin kelihatan zoom */
        border: none;
        display: block;
    }

    /* Empty */
    .file-empty {
        text-align: center;
        font-size: 0.875rem;
        color: #6b7280;
        font-style: italic;
        padding: 1rem;
    }
</style>
