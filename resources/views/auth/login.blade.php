<x-guest-layout>
    <div class="max-w-xl w-full  mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="p-8 md:p-12">

                <!-- Header -->
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        Login Akun Magang
                    </h2>
                    <p class="text-gray-500 text-sm md:text-base">
                        Masuk ke sistem absensi magang
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Error Message -->
                @if (session('error'))
                    <div
                        class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Form Container -->
                <div class="bg-gray-50 rounded-xl p-6 md:p-8 border border-gray-100">
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        <div class="space-y-6">
                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                    placeholder="contoh@email.com" required autofocus autocomplete="username"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 
                                    bg-white text-gray-900 placeholder-gray-400 
                                    focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                @if ($errors->has('email'))
                                    <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password" type="password" name="password" placeholder="Masukkan password"
                                    required autocomplete="current-password"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 
                                    bg-white text-gray-900 placeholder-gray-400 
                                    focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                @if ($errors->has('password'))
                                    <span
                                        class="text-red-500 text-sm mt-1 block">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="w-4 h-4 rounded border-gray-300 text-emerald-800 
                                    focus:ring-2 focus:ring-emerald-800 focus:ring-offset-0 transition-all cursor-pointer">
                                <label for="remember_me" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                    Ingat saya
                                </label>
                            </div>

                            <!-- Submit Button and Forgot Password -->
                            <div class="space-y-4">
                                <button type="submit" id="loginButton"
                                    class="w-full flex items-center justify-center gap-2 bg-emerald-800 hover:bg-emerald-900 
                                    text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-emerald-900/20 
                                    transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-emerald-800">
                                    <!-- Loading Spinner (hidden by default) -->
                                    <svg class="animate-spin h-5 w-5 hidden" id="loginSpinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span id="loginText">Masuk</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" id="loginIcon" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a href="{{ route('password.request') }}"
                                            class="text-sm text-emerald-800 hover:text-emerald-900 hover:underline font-medium transition-colors">
                                            Lupa password?
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>

                    <script>
                        document.getElementById('loginForm').addEventListener('submit', function(e) {
                            const button = document.getElementById('loginButton');
                            const spinner = document.getElementById('loginSpinner');
                            const text = document.getElementById('loginText');
                            const icon = document.getElementById('loginIcon');
                            
                            // Disable button and show loading state
                            button.disabled = true;
                            spinner.classList.remove('hidden');
                            text.textContent = 'Memproses...';
                            icon.classList.add('hidden');
                        });
                    </script>
                </div>

                <!-- Footer Link -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-emerald-800 hover:underline font-semibold">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
