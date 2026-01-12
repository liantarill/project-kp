<footer class="bg-primary text-white">
    <div class="max-w-7xl mx-auto px-6 py-12
                grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        <!-- Logo & Nama -->
        <div class="space-y-4">
            <div class="flex lg:block items-center gap-3 ">
                <img src="{{ asset('logo.png') }}" alt="PTPN Logo" class="h-12 w-auto">
                <div>
                    <p class="font-semibold leading-tight">
                        PT Perkebunan Nusantara I
                    </p>
                    <p class="text-sm text-green-200">
                        Regional 7 - Lampung
                    </p>
                </div>
            </div>
        </div>

        <!-- Menu Cepat -->
        <div>
            <h4 class="font-semibold mb-4">Menu Cepat</h4>
            <ul class="space-y-2 text-sm text-green-100">
                <li><a href="/" class="hover:underline">Beranda</a></li>
                <li><a href="/login" class="hover:underline">Login</a></li>
                <li><a href="/informasi" class="hover:underline">Informasi</a></li>
                <li><a href="/laporan" class="hover:underline">Laporan</a></li>
            </ul>
        </div>

        <!-- Hubungi Kami -->
        <div>
            <h4 class="font-semibold mb-4">Hubungi Kami</h4>
            <ul class="space-y-2 text-sm text-green-100">
                <li>Ruang SDM – PTPN I Regional 7</li>
                <li>Jl. Teuku Umar No.300, Kedaton, Kec. Kedaton, Kota Bandar Lampung, Lampung 35141</li>
                <li>
                    <a href="mailto:sdmptpn1@gmail.com" class="hover:underline">
                        sdmptpn1@gmail.com
                    </a>
                </li>
                <li>+62 822 6910 0376 (Lian)</li>
            </ul>
        </div>

        <!-- Media Sosial -->
        <div>
            <h4 class="font-semibold mb-4">Media Sosial</h4>
            <div class="flex gap-3 flex-wrap">
                @php
                    $icons = [
                        'instagram' => 'fa-brands fa-instagram',
                        'facebook' => 'fa-brands fa-facebook',
                        'x' => 'fa-brands fa-x-twitter',
                        'linkedin' => 'fa-brands fa-linkedin',
                        'tiktok' => 'fa-brands fa-tiktok',
                        'youtube' => 'fa-brands fa-youtube',
                    ];
                @endphp

                @foreach ($icons as $name => $symbol)
                    <a href="#"
                        class="w-10 h-10 flex items-center justify-center
              bg-white text-primary rounded-full
              hover:scale-110 transition">
                        <span class="sr-only">{{ $name }}</span>
                        <i class="{{ $symbol }}"></i>
                    </a>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Tim Developer Section -->
    <div class="border-t border-green-700">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <h4 class="font-semibold mb-3 text-center">Tim Developer</h4>
            <div class="flex justify-center gap-6 flex-wrap">

                @foreach (['Malsya Cantika', 'Aathifah D. C.', 'Yusuf Herlian A. R.'] as $developer)
                    <div class="flex items-center gap-2 text-sm text-green-100">
                        <i class="fa-solid fa-code"></i>
                        <span>{{ $developer }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-green-700">
        <div
            class="max-w-7xl mx-auto px-6 py-4
                    flex flex-col md:flex-row
                    justify-between items-center
                    text-sm text-green-200 gap-2">
            <span class="text-center">
                &copy; {{ date('Y') }} PT Perkebunan Nusantara I Regional 7.
                Semua hak dilindungi.
            </span>
            <div class="flex gap-4">
                <a href="#" class="hover:underline">Kebijakan Privasi</a>
                <a href="#" class="hover:underline">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>
