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
                E-Prakerin (Elektronik Praktek Kerja Industri)
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
                        <i class="fa-solid fa-arrow-right-to-bracket text-white text-2xl"></i>
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
                        <i class="fa-solid fa-circle-check text-white text-2xl"></i>
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
                        <i class="fa-solid fa-thumbtack text-white text-2xl"></i>
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
                <div class="flex items-center gap-4">
                    <div class="flex flex-col items-center flex-shrink-0">
                        <div class="w-16 h-16 bg-orange-300 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-arrow-right-to-bracket text-white text-2xl"></i>
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
                <div class="flex items-center gap-4">
                    <div class="flex flex-col items-center flex-shrink-0">
                        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-circle-check text-white text-2xl"></i>
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
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-blue-400 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-thumbtack text-white text-2xl"></i>
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <!-- Department 1 - Secretariat & Legal -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition 
                        p-5 sm:p-6 text-center 
                        flex flex-col h-full">
                    <div class="flex justify-center mb-5 sm:mb-6">
                        <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-building text-white text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Sekretariat dan Hukum (SKR)</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola administrasi dan aspek
                        hukum
                        perusahaan.</p>
                    <p class="text-xs text-gray-600 mb-4">6 kegiatan magang</p>
                    <button onclick="openBidangModal('skr')"
                        class="mt-auto inline-block px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                        Lihat Kegiatan →
                    </button>
                </div>

                <!-- Department 2 - Human Resources -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition 
                        p-5 sm:p-6 text-center 
                        flex flex-col h-full">
                    <div class="flex justify-center mb-5 sm:mb-6">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-brain text-white text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Sumber Daya Manusia (SDM)</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola perencanaan dan
                        pengembangan
                        karyawan.</p>
                    <p class="text-xs text-gray-600 mb-4">5 kegiatan magang</p>
                    <button onclick="openBidangModal('sdm')"
                        class="mt-auto inline-block px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                        Lihat Kegiatan →
                    </button>
                </div>

                <!-- Department 3 - Plantation & Processing Engineering -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition 
                        p-5 sm:p-6 text-center 
                        flex flex-col h-full">
                    <div class="flex justify-center mb-5 sm:mb-6">
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fa-brands fa-envira text-white text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Tanaman dan Teknik Pengolahan</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola operasional, budidaya dan
                        pengolahan hasil perkebunan.</p>
                    <p class="text-xs text-gray-600 mb-4">4 kegiatan magang</p>
                    <button onclick="openBidangModal('tanaman')"
                        class="mt-auto inline-block px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                        Lihat Kegiatan →
                    </button>
                </div>

                <!-- Department 4 - Accounting & Finance -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition 
                        p-5 sm:p-6 text-center 
                        flex flex-col h-full">
                    <div class="flex justify-center mb-5 sm:mb-6">
                        <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-hand-holding-dollar text-white text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Akuntansi dan Keuangan (ANK)</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola keuangan dan pelaporan
                        perusahaan.</p>
                    <p class="text-xs text-gray-600 mb-4">5 kegiatan magang</p>
                    <button onclick="openBidangModal('ank')"
                        class="mt-auto inline-block px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                        Lihat Kegiatan →
                    </button>
                </div>

                <!-- Department 5 - Asset Management & Marketing -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition 
                        p-5 sm:p-6 text-center 
                        flex flex-col h-full">
                    <div class="flex justify-center mb-5 sm:mb-6">
                        <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-box text-white text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Manajemen Aset dan Pemasaran</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola aset serta pemasaran produk
                        perusahaan.</p>
                    <p class="text-xs text-gray-600 mb-4">4 kegiatan magang</p>
                    <button onclick="openBidangModal('aset')"
                        class="mt-auto inline-block px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                        Lihat Kegiatan →
                    </button>
                </div>

                <!-- Department 6 - Compliance & Risk Management -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition 
                        p-5 sm:p-6 text-center 
                        flex flex-col h-full">
                    <div class="flex justify-center mb-5 sm:mb-6">
                        <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-triangle-exclamation text-white text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Kepatuhan dan Manajemen Risiko</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab untuk kepatuhan dan risiko dari praktik
                        manajemen.</p>
                    <p class="text-xs text-gray-600 mb-4">5 kegiatan magang</p>
                    <button onclick="openBidangModal('risiko')"
                        class="mt-auto inline-block px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                        Lihat Kegiatan →
                    </button>
                </div>

                <!-- Department 7 - Project Management Office -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition 
                        p-5 sm:p-6 text-center 
                        flex flex-col h-full">
                    <div class="flex justify-center mb-5 sm:mb-6">
                        <div class="w-16 h-16 bg-blue-400 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-clipboard text-white text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Project Management Office (PMO)</h3>
                    <p class="text-xs text-gray-500 mb-3">Kepala: biablablabla, S.Kom., M.M</p>
                    <p class="text-sm text-gray-700 mb-4">Bertanggung jawab dalam mengelola dan mengkoordinasikan
                        pelaksanaan proyek.</p>
                    <p class="text-xs text-gray-600 mb-4">6 kegiatan magang</p>
                    <button onclick="openBidangModal('pmo')"
                        class="mt-auto inline-block px-4 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                        Lihat Kegiatan →
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- MODAL BIDANG -->
    <div id="bidangModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">

        <div
            class="bg-white rounded-2xl w-full max-w-[95vw] sm:max-w-xl lg:max-w-2xl
            mx-auto overflow-hidden relative">

            <!-- Header -->
            <div id="modalHeader" class="text-white p-6 sm:p-6 relative flex flex-col items-center text-center">

                <button onclick="closeBidangModal()"
                    class="absolute top-4 right-4 text-white text-2xl hover:opacity-80">
                    ✕
                </button>

                <div class="text-center">
                    <div id="modalIconWrapper"
                        class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i id="modalIcon" class="fa-solid fa-building text-2xl"></i>
                    </div>
                    <h2 id="modalTitle" class="text-2xl font-bold"></h2>
                    <p id="modalHead" class="text-sm mt-2"></p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-5 space-y-4 text-sm text-gray-700">

                <div class="bg-green-50 rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100">
                            <i class="fa-solid fa-list-check text-green-600 text-lg"></i>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900">
                            Kegiatan Magang
                        </h3>
                    </div>

                    <ul id="modalActivities" class="space-y-3 text-gray-700"></ul>
                </div>


            </div>
        </div>
    </div>


    <script>
        const bidangData = {

            skr: {
                title: "Sekretariat dan Hukum (SKR)",
                head: "Kepala: Agus Faroni",
                desc: "Bertanggung jawab dalam mengelola administrasi dan aspek hukum perusahaan.",
                icon: "fa-building",
                color: "bg-red-600",
                intern: [
                    "Mendukung proses administrasi persuratan dan dokumen resmi perusahaan.",
                    "Menata dan menyimpan arsip dokumen hukum serta administrasi perusahaan.",
                    "Membantu pencatatan aktivitas surat menyurat dan memo internal.",
                    "Terlibat dalam penyusunan dokumen administrasi dan memo internal.",
                    "Membantu penyusunan rekap data administrasi dan dokumen legal.",
                    "Melaksanakan tugas pendukung lainnya sesuai arahan pembimbing."
                ]
            },

            sdm: {
                title: "Sumber Daya Manusia (SDM)",
                head: "Kepala: Ronald Sudrajat",
                desc: "Bertanggung jawab dalam mengelola perencanaan dan pengembangan karyawan.",
                icon: "fa-brain",
                color: "bg-blue-600",
                intern: [
                    "Membantu pengelolaan dan pembaruan data karyawan.",
                    "Mengorganisir dokumen kepegawaian dan administrasi SDM.",
                    "Mendukung pencatatan administrasi terkait kegiatan SDM.",
                    "Membantu penyusunan dokumen internal seperti memo atau pemberitahuan.",
                    "Menyusun ringkasan data absensi dan kegiatan pengembangan karyawan."
                ]
            },

            tanaman: {
                title: "Tanaman dan Teknik Pengolahan",
                head: "Kepala: Ari Askari",
                desc: "Bertanggung jawab dalam mengelola operasional, budidaya dan pengolahan hasil perkebunan.",
                icon: "fa-brands fa-envira",
                color: "bg-green-600",
                intern: [
                    "Membantu pencatatan data kegiatan operasional dan produksi lapangan.",
                    "Mengumpulkan dan mendokumentasikan laporan hasil panen.",
                    "Mendukung penyusunan laporan kegiatan budidaya dan pengolahan.",
                    "Membantu rekapitulasi data hasil produksi secara berkala."
                ]
            },

            ank: {
                title: "Akuntansi dan Keuangan (ANK)",
                head: "Kepala: Sondang Berliana Gultom",
                desc: "Bertanggung jawab dalam mengelola keuangan dan pelaporan perusahaan.",
                icon: "fa-hand-holding-dollar",
                color: "bg-yellow-500",
                intern: [
                    "Membantu administrasi pencatatan transaksi keuangan.",
                    "Mengelola dan menata dokumen pendukung transaksi dan laporan keuangan.",
                    "Mendukung pencatatan administrasi keuangan harian.",
                    "Membantu penyusunan laporan dan dokumen keuangan internal.",
                    "Menyusun ringkasan data keuangan secara periodik."
                ]
            },

            aset: {
                title: "Manajemen Aset dan Pemasaran",
                head: "Kepala: Sasmika Dwi Suryanto",
                desc: "Bertanggung jawab dalam mengelola aset serta pemasaran produk perusahaan.",
                icon: "fa-box",
                color: "bg-orange-600",
                intern: [
                    "Membantu pendataan dan administrasi aset perusahaan.",
                    "Menata dokumen terkait penjualan dan pemasaran produk.",
                    "Mendukung administrasi kegiatan promosi dan penjualan.",
                    "Menyusun rekap data aset dan aktivitas pemasaran."
                ]
            },

            risiko: {
                title: "Kepatuhan dan Manajemen Risiko",
                head: "Kepala: Yohanes P Siagian ",
                desc: "Bertanggung jawab untuk kepatuhan dan risiko dari praktik manajemen.",
                icon: "fa-triangle-exclamation",
                color: "bg-yellow-600",
                intern: [
                    "Membantu pengumpulan dan dokumentasi data terkait risiko operasional.",
                    "Mengelola arsip dokumen kepatuhan dan evaluasi internal.",
                    "Mendukung administrasi kegiatan pemantauan kepatuhan.",
                    "Membantu penyusunan dokumen evaluasi dan laporan risiko.",
                    "Menyusun ringkasan data kepatuhan dan manajemen risiko.",
                ]
            },

            pmo: {
                title: "Project Management Office (PMO)",
                head: "Kepala: Moehammad Baasith",
                desc: "Bertanggung jawab dalam mengelola dan mengkoordinasikan pelaksanaan proyek.",
                icon: "fa-clipboard",
                color: "bg-blue-500",
                intern: [
                    "Mendukung pengelolaan administrasi dan dokumentasi proyek.",
                    "Mengarsipkan dokumen perencanaan dan pelaksanaan proyek.",
                    "Membantu pencatatan perkembangan kegiatan proyek.",
                    "Membantu penyusunan laporan dan memo proyek.",
                    "Menyusun ringkasan progres dan capaian proyek.",
                    "Melaksanakan tugas pendukung sesuai arahan pembimbing PMO."
                ]
            }

        };
    </script>

    <script>
        function openBidangModal(id) {
            const data = bidangData[id];
            if (!data) return;

            document.getElementById('modalTitle').innerText = data.title;
            document.getElementById('modalHead').innerText = data.head;

            const header = document.getElementById("modalHeader");
            header.className = `text-white p-8 relative ${data.color}`;

            document.getElementById("modalTitle").innerText = data.title;
            document.getElementById("modalHead").innerText = data.head;

            const icon = document.getElementById('modalIcon');
            icon.className = `fa-solid ${data.icon} text-2xl`;

            const internList = document.getElementById('modalActivities');
            internList.innerHTML = '';
            data.intern.forEach((item, i) => {
                internList.innerHTML += `
            <li class="flex gap-3">
                <span class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs">${i + 1}</span>
                <span>${item}</span>
            </li>`;
            });

            const modal = document.getElementById('bidangModal');
            modal.classList.remove('hidden');
        }

        function closeBidangModal() {
            document.getElementById('bidangModal').classList.add('hidden');
        }

        document.getElementById('bidangModal').addEventListener('click', function(e) {
            if (e.target === this) closeBidangModal();
        });
    </script>


</x-guest-layout>
