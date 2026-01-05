<x-app-layout>
    <script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">


    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>

    <div class="min-h-screen bg-[#F8FAFC] pb-32">


        <main class="pt-20 px-5 max-w-md mx-auto space-y-8">

            <!-- Date Header -->
            <div class="flex flex-col items-center justify-center py-2 space-y-1">
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[11px] font-semibold tracking-wide uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    {{ now()->translatedFormat('l, d M Y') }}
                </span>
                <h2 class="text-2xl font-semibold text-slate-900 tracking-tight">Absensi Masuk</h2>
                <p class="text-sm text-slate-400">Silakan lengkapi data kehadiran Anda</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-4">
                    <div class="flex">
                        <span class="material-symbols-outlined text-red-500 text-[20px] mr-3">error</span>
                        <ul class="text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-xl p-4">
                    <div class="flex">
                        <span class="material-symbols-outlined text-emerald-500 text-[20px] mr-3">check_circle</span>
                        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('absensi.store') }}" enctype="multipart/form-data"
                id="attendanceForm">
                @csrf

                <!-- Hidden Fields -->
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <input type="hidden" name="status" id="statusInput" value="present">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="hidden" name="photo" id="photo">

                <!-- Status Selection -->
                <section class="space-y-3 mb-8">
                    <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider ml-1">Status
                        Kehadiran</label>
                    <div class="grid grid-cols-3 gap-3">
                        <button type="button" onclick="selectStatus('present')"
                            class="status-btn relative group flex flex-col items-center justify-center p-4 rounded-2xl bg-white border-2 border-emerald-500 shadow-soft transition-all duration-200 scale-105"
                            data-status="present">
                            <div class="absolute top-2 right-2 w-2 h-2 rounded-full bg-emerald-500"></div>
                            <span
                                class="material-symbols-outlined text-emerald-500 mb-2 text-[26px]">check_circle</span>
                            <span class="text-xs font-semibold text-slate-800">Hadir</span>
                        </button>
                        <button type="button" onclick="selectStatus('permission')"
                            class="status-btn flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 border border-transparent hover:bg-white hover:border-slate-200 transition-all duration-200 text-slate-400 hover:text-slate-600"
                            data-status="permission">
                            <span class="material-symbols-outlined mb-2 text-[26px]">assignment</span>
                            <span class="text-xs font-medium">Izin</span>
                        </button>
                        <button type="button" onclick="selectStatus('sick')"
                            class="status-btn flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 border border-transparent hover:bg-white hover:border-slate-200 transition-all duration-200 text-slate-400 hover:text-slate-600"
                            data-status="sick">
                            <span class="material-symbols-outlined mb-2 text-[26px]">local_hospital</span>
                            <span class="text-xs font-medium">Sakit</span>
                        </button>
                    </div>
                </section>

                <!-- Note Input -->
                <section class="space-y-3 mb-8">
                    <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider ml-1"
                        for="notes">Catatan</label>
                    <div class="relative group">
                        <textarea
                            class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm text-slate-700 placeholder:text-slate-300 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all resize-none shadow-sm"
                            id="notes" name="note" placeholder="Tambahkan catatan (opsional)..." rows="2"></textarea>
                        <div class="absolute bottom-3 right-3 pointer-events-none">
                            <span class="material-symbols-outlined text-slate-300 text-sm">edit</span>
                        </div>
                    </div>
                </section>

                <!-- Camera Warning -->
                <div id="cameraWarning" class="hidden bg-yellow-50 border-l-4 border-yellow-500 rounded-xl p-4 mb-8">
                    <div class="flex">
                        <span class="material-symbols-outlined text-yellow-500 text-[20px] mr-3">warning</span>
                        <div>
                            <p class="text-sm text-yellow-700 font-semibold">Kamera Tidak Aktif</p>
                            <p class="text-xs text-yellow-600 mt-1">Mohon izinkan akses kamera untuk melanjutkan
                                absensi.</p>
                        </div>
                    </div>
                </div>

                <!-- Camera Section -->
                <section class="space-y-3 mb-8" id="cameraSection">
                    <div class="flex items-center justify-between px-1">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Foto</label>
                    </div>
                    <div class="relative overflow-hidden rounded-3xl bg-slate-100 shadow-sm ring-1 ring-slate-100">
                        <video id="video" autoplay playsinline class="w-full aspect-[4/3] object-cover"
                            style="transform: scaleX(-1);"></video>

                        <canvas id="canvas" class="hidden"></canvas>

                        <img id="previewImage" class="hidden w-full aspect-[4/3] object-cover" />

                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none">
                        </div>

                        <div class="absolute bottom-0 left-0 right-0 p-4 flex items-center justify-between">
                            <div class="text-white">
                                <p class="text-[10px] font-medium opacity-80 uppercase tracking-wide">Diambil Pada</p>
                                <p class="text-xs font-semibold" id="photoTime">--:-- WIB</p>
                            </div>
                            <button type="button" onclick="takePhoto()" id="btnTakePhoto"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-md border border-white/20 transition-all text-white text-xs font-medium">
                                <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                                <span>Ambil</span>
                            </button>
                            <button type="button" onclick="retakePhoto()" id="btnRetake"
                                class="hidden flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-md border border-white/20 transition-all text-white text-xs font-medium">
                                <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                                <span>Ulang</span>
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Location Section -->
                <section class="space-y-3 mb-8" id="locationSection">
                    <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider ml-1">Lokasi
                        Anda</label>
                    <div class="bg-white rounded-3xl p-1.5 shadow-sm border border-slate-100">
                        <div class="relative w-full h-44 rounded-2xl overflow-hidden bg-slate-100 group">
                            <div id="attendance-map" class="hidden w-full h-full relative z-0"></div>

                            <div id="mapPlaceholder"
                                class="w-full h-full bg-slate-100 flex items-center justify-center absolute inset-0 z-10">
                                <p id="locationStatus" class="text-sm text-slate-600 text-center">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                    Mengambil lokasi...
                                </p>
                            </div>

                            <div class="absolute inset-0 flex items-center justify-center -mt-3 z-20 pointer-events-none"
                                id="locationPin">
                                {{-- <span
                                    class="material-symbols-outlined text-blue-600 text-4xl drop-shadow-lg">location_on</span> --}}
                            </div>

                            <div class="absolute bottom-3 left-3 right-3 z-30" id="locationInfo"
                                style="display: none;">
                                <div
                                    class="bg-white/95 backdrop-blur-sm p-3 rounded-xl shadow-sm border border-slate-100 flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0 text-emerald-600">
                                        <span class="material-symbols-outlined text-[18px]"
                                            id="locationIcon">check_circle</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate" id="locationText">Dalam
                                            area kantor</p>
                                        <p class="text-[10px] text-slate-500" id="locationDistance">Akurasi GPS: --
                                            meter</p>
                                    </div>
                                    <button type="button" onclick="requestLocation()"
                                        class="text-slate-400 hover:text-blue-600 transition-colors pointer-events-auto">
                                        <span class="material-symbols-outlined text-[20px]">refresh</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </form>
        </main>

        <!-- Bottom Submit Button -->
        <div
            class="fixed bottom-0 left-0 right-0 z-40 bg-white/80 backdrop-blur-xl border-t border-slate-200/60 pb-8 pt-4 px-6">
            <div class="max-w-md mx-auto">
                <button type="button" onclick="submitForm()" id="submitBtn"
                    class="w-full group bg-slate-900 hover:bg-slate-800 text-white font-semibold text-[15px] py-4 rounded-2xl shadow-lg transition-all active:scale-[0.99] flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    <span id="submitText">Kirim Absensi</span>
                    <span
                        class="material-symbols-outlined text-[18px] group-hover:translate-x-0.5 transition-transform">send</span>
                </button>
                <p class="text-[10px] text-center text-slate-400 mt-3 flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-[12px]">info</span>
                    Pastikan data sudah benar sebelum mengirim
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/attendance.js')
    @endpush
</x-app-layout>
