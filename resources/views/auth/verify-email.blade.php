<x-guest-layout>
    <div class="max-w-xl w-full mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="p-8 md:p-12">

                <!-- Header -->
                <div class="text-center mb-12">
                    <div class="mx-auto w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-8 w-8 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        Verifikasi Email
                    </h2>
                    <p class="text-gray-500 text-sm md:text-base">
                        Konfirmasi alamat email Anda
                    </p>
                </div>

                <!-- Info Message -->
                <div
                    class="mb-6 flex items-start gap-3 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
                    <svg class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm">
                        Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan
                        mengklik link yang baru saja kami kirimkan. Jika Anda tidak menerima email, kami dengan senang
                        hati akan mengirimkan yang baru.
                    </span>
                </div>

                <!-- Success Message -->
                @if (session('status') == 'verification-link-sent')
                    <div
                        class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">
                            Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
                        </span>
                    </div>
                @endif

                <!-- Actions Container -->
                <div class="bg-gray-50 rounded-xl p-6 md:p-8 border border-gray-100">
                    <div class="space-y-4">
                        <!-- Resend Verification Email -->
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-emerald-800 hover:bg-emerald-900 
                                text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-emerald-900/20 
                                transition-all active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span>Kirim Ulang Email Verifikasi</span>
                            </button>
                        </form>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-white hover:bg-gray-50 
                                text-gray-700 font-semibold py-3 px-8 rounded-lg border border-gray-300
                                transition-all active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Footer Help -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        Tidak menerima email? Periksa folder spam atau
                        <span class="text-emerald-800 font-semibold">kirim ulang</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
