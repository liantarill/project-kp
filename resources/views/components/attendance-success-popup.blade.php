<!-- Toast Overlay -->
<div id="toast-overlay"
    class="fixed inset-0 -mt-16 z-[9999] flex items-center justify-center bg-black/40 backdrop-blur-sm px-4 animate-fade-in">
    <!-- Toast Card -->
    <div
        class="toast-card bg-white w-full max-w-[420px] rounded-3xl shadow-2xl overflow-hidden border border-slate-200 animate-scale-in">
        <!-- Toast Header / Icon -->
        <div class="flex flex-col items-center pt-10 pb-6 px-6">
            <div class="bg-emerald-50 p-4 rounded-full mb-4">
                <span class="material-symbols-outlined text-emerald-500 text-6xl font-bold">check_circle</span>
            </div>
            <h2 class="text-slate-900 text-2xl font-extrabold tracking-tight text-center">
                Presensi Berhasil!
            </h2>
        </div>

        <!-- Toast Body -->
        <div class="px-8 pb-8">
            <p class="text-slate-600 text-center text-base font-normal leading-relaxed mb-8">
                {{ session('success') }}
            </p>

            <!-- Summary Section -->
            <div class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden">
                <div class="grid grid-cols-[100px_1fr] border-b border-slate-200 px-4 py-4">
                    <p class="text-slate-500 text-sm font-medium">Waktu</p>
                    <p class="text-slate-900 text-sm font-semibold">{{ now()->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                <div class="grid grid-cols-[100px_1fr] px-4 py-4">
                    <p class="text-slate-500 text-sm font-medium">Status</p>
                    <div class="flex items-center gap-2">
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        <p class="text-emerald-600 text-sm font-bold uppercase tracking-wide">Berhasil</p>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="mt-8">
                <button onclick="closeToast()"
                    class="w-full flex cursor-pointer items-center justify-center overflow-hidden rounded-2xl h-12 px-6 bg-slate-900 hover:bg-slate-800 text-white text-base font-bold leading-normal transition-all active:scale-[0.99] shadow-lg">
                    <span class="truncate">Tutup</span>
                </button>
            </div>
        </div>

        <!-- Decorative accent line -->
        <div class="h-1 w-full bg-gradient-to-r from-emerald-400 to-emerald-500"></div>
    </div>
</div>

<!-- Toast Animations -->
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes scaleOut {
        from {
            opacity: 1;
            transform: scale(1);
        }

        to {
            opacity: 0;
            transform: scale(0.95);
        }
    }

    .animate-scale-out {
        animation: scaleOut 0.3s ease-in forwards;
    }


    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-scale-in {
        animation: scaleIn 0.4s ease-out;
    }

    .animate-fade-out {
        animation: fadeOut 0.3s ease-out;
    }
</style>

<script>
    function closeToast() {
        const overlay = document.getElementById('toast-overlay');
        const card = overlay?.querySelector('.toast-card');

        if (!overlay || !card) return;

        overlay.classList.remove('animate-fade-in');
        overlay.classList.add('animate-fade-out');

        card.classList.remove('animate-scale-in');
        card.classList.add('animate-scale-out');

        setTimeout(() => {
            overlay.remove();
        }, 300);
    }

    setTimeout(closeToast, 5000);

    // Close on overlay click
    document.getElementById('toast-overlay')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeToast();
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const overlay = document.getElementById('toast-overlay');
            if (overlay) {
                closeToast();
            }
        }
    });
</script>
