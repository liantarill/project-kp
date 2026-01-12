<x-guest-layout>
    <!-- Background Image Section -->
    <section
        class="relative min-h-screen w-full bg-cover bg-center bg-no-repeat
           flex items-center justify-center pt-24"
        style="background-image: url('company.jpg')">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-green-900/60"></div>

        <!-- Content -->
        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Sistem Absensi Magang Digital
            </h1>

            <p class="text-base md:text-lg text-green-100 mb-8">
                Solusi pencatatan kehadiran siswa dan mahasiswa magang di
                <span class="font-semibold">PTPN I Regional 7</span>
                berbasis lokasi dan foto bukti secara real-time.
            </p>

            <a href="{{ auth()->user() ? route('absensi.index') : route('login') }}"
                class="inline-block bg-green-700 hover:bg-green-800
                  text-white font-medium px-8 py-3 rounded-full shadow-md">
                Masuk Absensi
            </a>
        </div>
    </section>

    <!-- Fitur Utama Section -->
    <section class="w-full bg-white py-24">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Title -->
            <h2 class="text-4xl font-bold text-gray-900 mb-3">Fitur Utama</h2>
            <div class="h-1 w-20 bg-green-700 mb-16"></div>

            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                <!-- Card 1 -->
                <div class="bg-white rounded-xl px-8 py-10 text-center shadow-md hover:shadow-lg transition">
                    <span
                        class="inline-block mb-4 px-4 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
                        Otomatis
                    </span>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Absensi Lokasi</h3>
                    <p class="text-gray-600">
                        Lokasi kehadiran tercatat secara otomatis saat absensi.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-xl px-8 py-10 text-center shadow-md hover:shadow-lg transition">
                    <span
                        class="inline-block mb-4 px-4 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
                        Real-Time
                    </span>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Foto Bukti Kehadiran</h3>
                    <p class="text-gray-600">
                        Ambil foto langsung sebagai bukti absensi.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-xl px-8 py-10 text-center shadow-md hover:shadow-lg transition">
                    <span
                        class="inline-block mb-4 px-4 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
                        Aman
                    </span>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Data Tersimpan</h3>
                    <p class="text-gray-600">
                        Riwayat absensi tersimpan dan dapat diakses kembali.
                    </p>
                </div>

            </div>
        </div>
    </section>


    <!-- Tahapan Penggunaan Section -->
    <section class="w-full bg-white py-12 md:py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Tahapan Penggunaan</h2>
            <div class="h-1 w-16 sm:w-20 bg-green-700 mb-8 sm:mb-12 lg:mb-20"></div>

            <!-- Desktop & Tablet Layout (md and up) -->
            <div class="hidden md:flex items-center justify-center gap-4 lg:gap-8">

                <!-- STEP 1 -->
                <div class="flex flex-col items-center flex-1 max-w-xs">
                    <div
                        class="w-20 h-20 lg:w-24 lg:h-24 bg-orange-300 rounded-2xl lg:rounded-3xl flex items-center justify-center shadow-lg mb-4 lg:mb-6">
                        <svg class="w-8 h-8 lg:w-10 lg:h-10 text-white" fill="none" stroke="currentColor"
                            stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg lg:text-xl font-semibold text-gray-900 mb-2 lg:mb-3">Registrasi</h3>
                    <p class="text-sm lg:text-base text-gray-600 text-center">
                        Buat akun menggunakan email aktif dan isi data diri sesuai instansi asal.
                    </p>
                </div>

                <!-- CONNECTOR 1 -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="w-2 h-2 lg:w-2.5 lg:h-2.5 bg-orange-300 rounded-full"></span>
                    <div class="w-16 lg:w-24 border-t-2 border-dashed border-gray-300"></div>
                    <span class="w-2 h-2 lg:w-2.5 lg:h-2.5 bg-green-500 rounded-full"></span>
                </div>

                <!-- STEP 2 -->
                <div class="flex flex-col items-center flex-1 max-w-xs">
                    <div
                        class="w-20 h-20 lg:w-24 lg:h-24 bg-green-500 rounded-2xl lg:rounded-3xl flex items-center justify-center shadow-lg mb-4 lg:mb-6">
                        <svg class="w-8 h-8 lg:w-10 lg:h-10 text-white" fill="none" stroke="currentColor"
                            stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg lg:text-xl font-semibold text-gray-900 mb-2 lg:mb-3">Verifikasi</h3>
                    <p class="text-sm lg:text-base text-gray-600 text-center">
                        Tunggu persetujuan admin atau verifikasi email untuk mengaktifkan akun.
                    </p>
                </div>

                <!-- CONNECTOR 2 -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="w-2 h-2 lg:w-2.5 lg:h-2.5 bg-green-500 rounded-full"></span>
                    <div class="w-16 lg:w-24 border-t-2 border-dashed border-gray-300"></div>
                    <span class="w-2 h-2 lg:w-2.5 lg:h-2.5 bg-blue-400 rounded-full"></span>
                </div>

                <!-- STEP 3 -->
                <div class="flex flex-col items-center flex-1 max-w-xs">
                    <div
                        class="w-20 h-20 lg:w-24 lg:h-24 bg-blue-400 rounded-2xl lg:rounded-3xl flex items-center justify-center shadow-lg mb-4 lg:mb-6">
                        <svg class="w-8 h-8 lg:w-10 lg:h-10 text-white" fill="none" stroke="currentColor"
                            stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg lg:text-xl font-semibold text-gray-900 mb-2 lg:mb-3">Absensi Rutin</h3>
                    <p class="text-sm lg:text-base text-gray-600 text-center">
                        Lakukan absensi magangmu setiap hari tepat waktu dan sesuai lokasi
                    </p>
                </div>

            </div>

            <!-- Mobile Layout (below md) -->
            <div class="flex flex-col md:hidden space-y-8">

                <!-- STEP 1 -->
                <div class="flex items-start gap-4">
                    <div class="flex flex-col items-center flex-shrink-0">
                        <div class="w-16 h-16 bg-orange-300 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                        <div class="w-0.5 h-12 bg-gray-300 mt-3"></div>
                    </div>
                    <div class="flex-1 pt-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Registrasi</h3>
                        <p class="text-sm text-gray-600">
                            Buat akun menggunakan email aktif dan isi data diri sesuai instansi asal.
                        </p>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div class="flex items-start gap-4">
                    <div class="flex flex-col items-center flex-shrink-0">
                        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="w-0.5 h-12 bg-gray-300 mt-3"></div>
                    </div>
                    <div class="flex-1 pt-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Verifikasi</h3>
                        <p class="text-sm text-gray-600">
                            Tunggu persetujuan admin atau verifikasi email untuk mengaktifkan akun.
                        </p>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-blue-400 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 pt-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Absensi Rutin</h3>
                        <p class="text-sm text-gray-600">
                            Lakukan absensi magangmu setiap hari tepat waktu dan sesuai lokasi
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Bidang Penempatan Section -->
    <section class="w-full bg-gradient-to-br from-green-50 to-white py-24">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-4xl font-bold text-gray-900 mb-2">Bidang Penempatan</h2>
            <div class="h-1 w-20 bg-green-700 mb-16"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Department 1 - Secretariat & Legal -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition p-6 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 22h16v-2H4v2zm2-4h12V9H6v9zm6-16L2 7v2h20V7L12 2z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Sekretariat dan Hukum (SKR)</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola administrasi dan aspek
                        hukum
                        perusahaan.</p>
                    <p class="text-xs text-gray-600 mb-4">6 tugas • 8 kegiatan magang</p>
                    <a href="#"
                        class="inline-block px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-full hover:bg-green-200">Lihat
                        Detail →</a>
                </div>

                <!-- Department 2 - Human Resources -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition p-6 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Sumber Daya Manusia (SDM)</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola perencanaan dan
                        pengembangan
                        karyawan.</p>
                    <p class="text-xs text-gray-600 mb-4">9 tugas • 8 kegiatan magang</p>
                    <a href="#"
                        class="inline-block px-4 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full hover:bg-blue-200">Lihat
                        Detail →</a>
                </div>

                <!-- Department 3 - Plantation & Processing Engineering -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition p-6 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.92 7.02C17.45 4.18 14.97 2 12 2c-2.97 0-5.45 2.18-5.92 5.02C5.97 7.42 4.25 9.22 4.25 11.5c0 2.78 2.22 5 5 5h.93v-1.93H9c-1.71 0-3.07-1.36-3.07-3.07 0-1.5 1.08-2.76 2.51-2.99.4-.23.75-.62 1.01-1.1.26-.48.6-.98 1.04-1.37.44-.38.98-.66 1.56-.83.58-.17 1.19-.23 1.75-.11.56.12 1.08.37 1.54.76.46.39.82.88 1.07 1.42.25.54.4 1.12.44 1.71.04.59-.04 1.18-.2 1.74s-.5 1.08-.91 1.52h-.93V18.5h.93c2.78 0 5-2.22 5-5 0-2.28-1.72-4.08-3.88-4.48z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Tanaman dan Teknik Pengolahan</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola operasional, budidaya dan
                        pengolahan hasil perkebunan.</p>
                    <p class="text-xs text-gray-600 mb-4">9 tugas • 8 kegiatan magang</p>
                    <a href="#"
                        class="inline-block px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-full hover:bg-green-200">Lihat
                        Detail →</a>
                </div>

                <!-- Department 4 - Accounting & Finance -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition p-6 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Akuntansi dan Keuangan (ANK)</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola keuangan dan pelaporan
                        perusahaan.</p>
                    <p class="text-xs text-gray-600 mb-4">9 tugas • 10 kegiatan magang</p>
                    <a href="#"
                        class="inline-block px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full hover:bg-yellow-200">Lihat
                        Detail →</a>
                </div>

                <!-- Department 5 - Asset Management & Marketing -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition p-6 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path
                                    d="M21 16V8c0-.5-.37-.93-.88-1L12 3.44 3.88 7C3.37 7.07 3 7.5 3 8v8c0 .5.37.93.88 1l8.12 3.56 8.12-3.56c.51-.07.88-.5.88-1zm-9 1.15l-6-2.6v-5.02l6-2.6 6 2.6v5.02l-6 2.6z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Manajemen Aset dan Pemasaran</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola aset serta pemasaran produk
                        perusahaan.</p>
                    <p class="text-xs text-gray-600 mb-4">9 tugas • 8 kegiatan magang</p>
                    <a href="#"
                        class="inline-block px-4 py-2 bg-orange-100 text-orange-700 text-sm font-semibold rounded-full hover:bg-orange-200">Lihat
                        Detail →</a>
                </div>

                <!-- Department 6 - Compliance & Risk Management -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition p-6 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 16c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7zm0-12c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Kepatuhan dan Manajemen Risiko</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertu apana untuk kepatuhan dan risiko dari praktik
                        manajemen.</p>
                    <p class="text-xs text-gray-600 mb-4">9 tugas • 8 kegiatan magang</p>
                    <a href="#"
                        class="inline-block px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-semibold rounded-full hover:bg-yellow-200">Lihat
                        Detail →</a>
                </div>

                <!-- Department 7 - Project Management Office -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition p-6 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-blue-400 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 9h4v2h-4v-2zm0 4h4v2h-4v-2zM7 7h10v2H7z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Project Management Office (PMO)</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola dan mengkoordinasikan
                        pelaksanaan proyek.</p>
                    <p class="text-xs text-gray-600 mb-4">9 tugas • 10 kegiatan magang</p>
                    <a href="#"
                        class="inline-block px-4 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full hover:bg-blue-200">Lihat
                        Detail →</a>
                </div>
            </div>
        </div>
    </section>

</x-guest-layout>
