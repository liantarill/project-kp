<x-guest-layout>
    <div class="max-w-xl w-full mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="p-8 md:p-12">

                <!-- Header -->
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        Reset Password
                    </h2>
                    <p class="text-gray-500 text-sm md:text-base">
                        Masukkan password baru untuk akun Anda
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Form Container -->
                <div class="bg-gray-50 rounded-xl p-6 md:p-8 border border-gray-100">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="space-y-6">
                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input id="email" type="email" name="email"
                                    value="{{ old('email', $request->email) }}" placeholder="contoh@email.com" required
                                    autofocus autocomplete="username"
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
                                    Password Baru <span class="text-red-500">*</span>
                                </label>
                                <input id="password" type="password" name="password"
                                    placeholder="Masukkan password baru" required autocomplete="new-password"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 
                                    bg-white text-gray-900 placeholder-gray-400 
                                    focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                @if ($errors->has('password'))
                                    <span
                                        class="text-red-500 text-sm mt-1 block">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    placeholder="Masukkan ulang password baru" required autocomplete="new-password"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 
                                    bg-white text-gray-900 placeholder-gray-400 
                                    focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                @if ($errors->has('password_confirmation'))
                                    <span
                                        class="text-red-500 text-sm mt-1 block">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="space-y-4">
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 bg-emerald-800 hover:bg-emerald-900 
                                    text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-emerald-900/20 
                                    transition-all active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    <span>Reset Password</span>
                                </button>
                            </div>
                        </div>
                    </form>
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
