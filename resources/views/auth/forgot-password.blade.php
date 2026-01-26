<x-guest-layout>
    <div class="max-w-xl w-full mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="p-8 md:p-12">

                <!-- Header -->
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        Lupa Password
                    </h2>
                    <p class="text-gray-500 text-sm md:text-base">
                        Masukkan email Anda untuk reset password
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
                        Tidak masalah! Kami akan mengirimkan link reset password ke email Anda.
                    </span>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Form Container -->
                <div class="bg-gray-50 rounded-xl p-6 md:p-8 border border-gray-100">
                    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                        @csrf

                        <div class="space-y-6">
                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                    placeholder="contoh@email.com" required autofocus autocomplete="email"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 
                                    bg-white text-gray-900 placeholder-gray-400 
                                    focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                @if ($errors->has('email'))
                                    <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="space-y-4">
                                <button type="submit" id="forgotPasswordButton"
                                    class="w-full flex items-center justify-center gap-2 bg-emerald-800 hover:bg-emerald-900 
                                    text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-emerald-900/20 
                                    transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-emerald-800">
                                    <!-- Loading Spinner -->
                                    <svg class="animate-spin h-5 w-5 hidden" id="forgotPasswordSpinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" id="forgotPasswordIcon" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span id="forgotPasswordText">Kirim Link</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <script>
                        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
                            const button = document.getElementById('forgotPasswordButton');
                            const spinner = document.getElementById('forgotPasswordSpinner');
                            const text = document.getElementById('forgotPasswordText');
                            const icon = document.getElementById('forgotPasswordIcon');
                            
                            button.disabled = true;
                            spinner.classList.remove('hidden');
                            text.textContent = 'Mengirim...';
                            icon.classList.add('hidden');
                        });
                    </script>
                </div>

                <!-- Footer Link -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        Sudah ingat password?
                        <a href="{{ route('login') }}" class="text-emerald-800 hover:underline font-semibold">
                            Kembali ke login
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
