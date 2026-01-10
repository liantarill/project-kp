<div>
    @vite(['resources/css/registration.css', 'resources/js/registration.js'])

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="p-8 md:p-12">

                <!-- Loading Overlay -->
                <div wire:loading.flex wire:target="nextStep,previousStep,submit"
                    class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 items-center justify-center">
                    <div class="bg-white rounded-2xl p-8 shadow-2xl max-w-sm mx-4">
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <div class="w-16 h-16 border-4 border-emerald-200 rounded-full"></div>
                                <div
                                    class="w-16 h-16 border-4 border-emerald-800 rounded-full animate-spin border-t-transparent absolute top-0 left-0">
                                </div>
                            </div>
                            <p class="mt-4 text-gray-700 font-semibold">Memproses...</p>
                            <p class="text-sm text-gray-500">Mohon tunggu sebentar</p>
                        </div>
                    </div>
                </div>

                <!-- Header -->
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        Daftar Akun Magang
                    </h2>
                    <p class="text-gray-500 text-sm md:text-base">
                        Lengkapi data diri Anda untuk mendaftar sebagai peserta magang
                    </p>
                </div>

                <!-- Progress Bar -->
                <div class="flex justify-between items-start mb-12 max-w-2xl mx-auto">
                    @php
                        $steps = [
                            ['number' => 1, 'title' => 'Data Akun', 'subtitle' => 'Informasi login'],
                            ['number' => 2, 'title' => 'Data Personal', 'subtitle' => 'Informasi pribadi'],
                            ['number' => 3, 'title' => 'Dokumen', 'subtitle' => 'Upload berkas'],
                            ['number' => 4, 'title' => 'Konfirmasi', 'subtitle' => 'Verifikasi data'],
                        ];
                    @endphp

                    @foreach ($steps as $index => $step)
                        <div class="flex flex-col items-center flex-1">
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center font-bold mb-2 transition-all
                                {{ $currentStep >= $step['number']
                                    ? 'bg-emerald-800 text-white shadow-lg shadow-emerald-900/20'
                                    : 'border-2 border-gray-200 text-gray-400' }}">
                                {{ $step['number'] }}
                            </div>
                            <span
                                class="text-xs text-center font-bold transition-colors
                                {{ $currentStep >= $step['number'] ? 'text-emerald-800' : 'text-gray-400' }}">
                                {{ $step['title'] }}
                            </span>
                            <span class="text-[10px] text-gray-400 text-center">
                                {{ $step['subtitle'] }}
                            </span>
                        </div>

                        @if ($index < count($steps) - 1)
                            <div
                                class="mt-5 h-px flex-1 mx-2 transition-colors
                                {{ $currentStep > $step['number'] ? 'bg-emerald-800' : 'bg-gray-200' }}">
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Form Container -->
                <div class="bg-gray-50 rounded-xl p-6 md:p-8 border border-gray-100">

                    <form wire:submit.prevent="submit">

                        {{-- Step 1: Account Information --}}
                        <div class="{{ $currentStep == 1 ? '' : 'hidden' }}">
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900">Data Akun</h3>
                                <p class="text-sm text-gray-500">
                                    Buat akun untuk mengakses sistem absensi magang
                                </p>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="name" id="name" type="text"
                                        placeholder="Masukkan nama lengkap sesuai KTP"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 
                                        bg-white text-gray-900 placeholder-gray-400 
                                        focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                    @error('name')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="email" id="email" type="email"
                                        placeholder="contoh@email.com"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 
                                        bg-white text-gray-900 placeholder-gray-400 
                                        focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                    @error('email')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                            Password <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="password" id="password" type="password"
                                            placeholder="Minimal 8 karakter"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 
                                            bg-white text-gray-900 placeholder-gray-400 
                                            focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                        @error('password')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation"
                                            class="block text-sm font-semibold text-gray-700 mb-1.5">
                                            Konfirmasi Password <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="password_confirmation" id="password_confirmation"
                                            type="password" placeholder="Ulangi password"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 
                                            bg-white text-gray-900 placeholder-gray-400 
                                            focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Education Information --}}
                        <div class="{{ $currentStep == 2 ? '' : 'hidden' }}">

                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900">Data Personal</h3>
                                <p class="text-sm text-gray-500">
                                    Informasi pendidikan dan kontak pribadi Anda
                                </p>
                            </div>

                            <div class="space-y-6">

                                <!-- Institusi dengan style Searchable Select -->
                                <div class="select-wrapper" data-select-id="institution_id" wire:ignore>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Nama Kampus/Sekolah <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <button type="button"
                                            class="select-trigger w-full px-4 py-3 rounded-lg border border-gray-300 bg-white text-left
                                            hover:bg-gray-50 focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none
                                            flex items-center justify-between group">
                                            <span class="select-value" data-placeholder="Pilih kampus/sekolah...">
                                                Pilih kampus/sekolah...
                                            </span>
                                            <svg class="w-5 h-5 text-gray-400 transition-transform group-hover:text-gray-600"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div
                                            class="select-dropdown hidden absolute z-50 w-full mt-2 bg-white rounded-lg shadow-xl border border-gray-200">
                                            <div class="p-2 border-b border-gray-200">
                                                <div class="relative">
                                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    <input type="text"
                                                        class="select-search w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 
                                                        focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none text-sm"
                                                        placeholder="Cari..." />
                                                </div>
                                            </div>
                                            <div class="select-options max-h-60 overflow-y-auto p-1"></div>
                                        </div>
                                    </div>
                                    <select wire:model="institution_id" id="institution_id" class="hidden">
                                        <option value=""></option>
                                        @foreach ($institutions as $institution)
                                            <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('institution_id')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- Jenjang Pendidikan dengan style Searchable Select -->
                                <div class="select-wrapper" data-select-id="level" wire:ignore>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Jenjang Pendidikan <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <button type="button"
                                            class="select-trigger w-full px-4 py-3 rounded-lg border border-gray-300 bg-white text-left
                                            hover:bg-gray-50 focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none
                                            flex items-center justify-between group">
                                            <span class="select-value" data-placeholder="Pilih jenjang pendidikan...">
                                                Pilih jenjang pendidikan...
                                            </span>
                                            <svg class="w-5 h-5 text-gray-400 transition-transform group-hover:text-gray-600"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div
                                            class="select-dropdown hidden absolute z-50 w-full mt-2 bg-white rounded-lg shadow-xl border border-gray-200">
                                            <div class="p-2 border-b border-gray-200">
                                                <div class="relative">
                                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    <input type="text"
                                                        class="select-search w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 
                                                        focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none text-sm"
                                                        placeholder="Cari..." />
                                                </div>
                                            </div>
                                            <div class="select-options max-h-60 overflow-y-auto p-1"></div>
                                        </div>
                                    </div>
                                    <select wire:model="level" id="level" class="hidden">
                                        <option value=""></option>
                                        @foreach (['SMA', 'D1', 'D2', 'D3', 'D4', 'S1'] as $lvl)
                                            <option value="{{ $lvl }}">{{ $lvl }}</option>
                                        @endforeach
                                    </select>
                                    @error('level')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>



                                <!-- Jurusan -->
                                <div>
                                    <label for="major" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Jurusan <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="major" id="major" type="text"
                                        placeholder="Masukkan jurusan Anda"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 
                bg-white text-gray-900 placeholder-gray-400 
                focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                    @error('major')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- No. Telepon -->
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        No. Telepon <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="phone" id="phone" type="text"
                                        placeholder="08xxxxxxxxxx"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 
                bg-white text-gray-900 placeholder-gray-400 
                focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                    @error('phone')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>


                        {{-- Step 3: Internship Information --}}
                        <div class="{{ $currentStep == 3 ? '' : 'hidden' }}">
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900">Dokumen Magang</h3>
                                <p class="text-sm text-gray-500">
                                    Upload berkas dan tentukan periode magang Anda
                                </p>
                            </div>

                            <div class="space-y-6">
                                <!-- Divisi dengan style Searchable Select -->
                                <div class="select-wrapper" data-select-id="department_id" wire:ignore>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Divisi <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <button type="button"
                                            class="select-trigger w-full px-4 py-3 rounded-lg border border-gray-300 bg-white text-left
                                            hover:bg-gray-50 focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none
                                            flex items-center justify-between group">
                                            <span class="select-value" data-placeholder="Pilih divisi...">
                                                Pilih divisi...
                                            </span>
                                            <svg class="w-5 h-5 text-gray-400 transition-transform group-hover:text-gray-600"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div
                                            class="select-dropdown hidden absolute z-50 w-full mt-2 bg-white rounded-lg shadow-xl border border-gray-200">
                                            <div class="p-2 border-b border-gray-200">
                                                <div class="relative">
                                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    <input type="text"
                                                        class="select-search w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 
                                                        focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none text-sm"
                                                        placeholder="Cari..." />
                                                </div>
                                            </div>
                                            <div class="select-options max-h-60 overflow-y-auto p-1"></div>
                                        </div>
                                    </div>
                                    <select wire:model="department_id" id="department_id" class="hidden">
                                        <option value=""></option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Tanggal Mulai dan Selesai -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="start_date"
                                            class="block text-sm font-semibold text-gray-700 mb-1.5">
                                            Tanggal Mulai <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="start_date" id="start_date" type="date"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 
                    bg-white text-gray-900 
                    focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                        @error('start_date')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="end_date"
                                            class="block text-sm font-semibold text-gray-700 mb-1.5">
                                            Tanggal Selesai <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="end_date" id="end_date" type="date"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 
                    bg-white text-gray-900 
                    focus:ring-2 focus:ring-emerald-800 focus:border-transparent transition-all outline-none" />
                                        @error('end_date')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Bukti Penerimaan -->
                                <div>
                                    <label for="acceptance_proof"
                                        class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Bukti Penerimaan <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="acceptance_proof" id="acceptance_proof" type="file"
                                        accept="application/pdf,image/png,image/jpg,image/jpeg"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 
                bg-white text-gray-900 
                file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 
                file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 
                hover:file:bg-emerald-100 transition-all" />
                                    @error('acceptance_proof')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror

                                    <!-- File Upload Loading -->
                                    <div wire:loading wire:target="acceptance_proof"
                                        class="mt-3 flex items-center gap-2 text-emerald-700 bg-emerald-50 px-4 py-2 rounded-lg">
                                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <span class="text-sm font-medium">Mengupload file...</span>
                                    </div>

                                    <!-- File Selected -->
                                    @if ($acceptance_proof && !$errors->has('acceptance_proof'))
                                        <div wire:loading.remove wire:target="acceptance_proof"
                                            class="mt-3 flex items-center gap-2 text-green-700 bg-green-50 px-4 py-2 rounded-lg">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="text-sm font-medium">
                                                File dipilih: {{ $acceptance_proof->getClientOriginalName() }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        {{-- Step 4: Confirmation --}}
                        <div class="{{ $currentStep == 4 ? '' : 'hidden' }}">
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900">Konfirmasi Data</h3>
                                <p class="text-sm text-gray-500">
                                    Periksa kembali data Anda sebelum mendaftar
                                </p>
                            </div>

                            <div class="bg-white rounded-lg p-6 space-y-4 border border-gray-200">
                                <div class="border-b border-gray-200 pb-3">
                                    <h4 class="font-semibold text-gray-900 mb-3">Informasi Akun</h4>
                                    <div class="space-y-2 text-sm">
                                        <p><span class="text-gray-500">Nama:</span> <span
                                                class="font-medium text-gray-900">{{ $name }}</span>
                                        </p>
                                        <p><span class="text-gray-500">Email:</span> <span
                                                class="font-medium text-gray-900">{{ $email }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="border-b border-gray-200 pb-3">
                                    <h4 class="font-semibold text-gray-900 mb-3">Informasi Pendidikan
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <p><span class="text-gray-500">Jenjang:</span> <span
                                                class="font-medium text-gray-900">{{ $level }}</span>
                                        </p>
                                        <p><span class="text-gray-500">Institusi:</span> <span
                                                class="font-medium text-gray-900">{{ $institutions->find($institution_id)?->name }}</span>
                                        </p>
                                        <p><span class="text-gray-500">Jurusan:</span> <span
                                                class="font-medium text-gray-900">{{ $major }}</span>
                                        </p>
                                        <p><span class="text-gray-500">No. Telepon:</span> <span
                                                class="font-medium text-gray-900">{{ $phone }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-3">Informasi Magang</h4>
                                    <div class="space-y-2 text-sm">
                                        <p><span class="text-gray-500">Divisi:</span> <span
                                                class="font-medium text-gray-900">{{ $departments->find($department_id)?->name }}</span>
                                        </p>
                                        <p><span class="text-gray-500">Periode:</span> <span
                                                class="font-medium text-gray-900">{{ $start_date }}
                                                s/d {{ $end_date }}</span></p>
                                        <p><span class="text-gray-500">Bukti Penerimaan:</span> <span
                                                class="font-medium text-gray-900">{{ $acceptance_proof?->getClientOriginalName() }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Navigation Buttons --}}
                        <div class="flex justify-between pt-6">
                            @if ($currentStep > 1)
                                <button type="button" wire:click="previousStep"
                                    class="px-6 py-3 bg-gray-200 text-gray-700 
                                    font-bold rounded-lg hover:bg-gray-300 transition-all 
                                    disabled:opacity-50 disabled:cursor-not-allowed"
                                    loading.attr="disabled">
                                    <span wire:loading.remove wire:target="previousStep">Kembali</span>
                                    <span wire:loading wire:target="previousStep">Memproses...</span>
                                </button>
                            @else
                                <div></div>
                            @endif

                            @if ($currentStep < $totalSteps)
                                <button type="button" wire:click="nextStep"
                                    class="flex items-center gap-2 bg-emerald-800 hover:bg-emerald-900 text-white 
                                    font-bold py-3 px-8 rounded-lg shadow-lg shadow-emerald-900/20 transition-all 
                                    active:scale-95 group disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="nextStep">Lanjutkan</span>
                                    <span wire:loading wire:target="nextStep">Memproses...</span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 transition-transform group-hover:translate-x-1"
                                        viewBox="0 0 20 20" fill="currentColor" wire:loading.remove
                                        wire:target="nextStep">
                                        <path fill-rule="evenodd"
                                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 
                                    rounded-lg shadow-lg transition-all active:scale-95 
                                    disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="submit">Daftar Sekarang</span>
                                    <span wire:loading wire:target="submit">Mendaftar...</span>
                                </button>
                            @endif
                        </div>

                    </form>
                </div>

                <!-- Footer Link -->
                <p class="text-sm text-center mt-6  text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-emerald-800 hover:underline font-semibold">
                        Login di sini
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
