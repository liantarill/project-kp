<!-- Photo Preview Popup -->
<div id="photo-popup" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl relative">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
            <h2 class="text-lg font-semibold text-slate-900">Foto Kehadiran</h2>
            <button onclick="closePhotoPopup()"
                class="w-9 h-9 rounded-full flex items-center justify-center hover:bg-slate-100 transition-colors text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 flex items-center justify-center bg-slate-50">
            <img id="photo-popup-image" src="" alt="Foto Kehadiran"
                class="max-h-[70vh] w-auto rounded-xl shadow-md object-contain">
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-100">
            <button onclick="closePhotoPopup()"
                class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold transition-all">
                Tutup
            </button>
        </div>

    </div>
</div>
<script>
    function openPhotoPopup(src) {
        const popup = document.getElementById('photo-popup');
        const image = document.getElementById('photo-popup-image');

        image.src = src;
        popup.classList.remove('hidden');
        popup.classList.add('flex');
    }

    function closePhotoPopup() {
        const popup = document.getElementById('photo-popup');
        popup.classList.add('hidden');
        popup.classList.remove('flex');
    }

    // Close on outside click
    document.getElementById('photo-popup')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePhotoPopup();
        }
    });

    // Close on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const popup = document.getElementById('photo-popup');
            if (popup && !popup.classList.contains('hidden')) {
                closePhotoPopup();
            }
        }
    });
</script>
