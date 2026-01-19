<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <x-slot name="header">
            <h1 class="font-bold text-2xl sm:text-3xl text-gray-800 leading-tight">
                Profile Saya
            </h1>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi profil dan akun Anda</p>
        </x-slot>

        <!-- Success Message -->
        @if (session('success'))
            <div
                class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-20">
                    <div class="text-center">
                        <!-- Avatar -->
                        <div class="relative inline-block mb-4">
                            <div
                                class="w-32 h-32 rounded-full bg-gradient-to-br from-primary-light to-primary flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div
                                class="absolute bottom-0 right-0 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg">
                                @if ($user->status === 'active')
                                    <span class="w-6 h-6 bg-green-500 rounded-full"></span>
                                @elseif($user->status === 'pending')
                                    <span class="w-6 h-6 bg-yellow-500 rounded-full"></span>
                                @elseif($user->status === 'completed')
                                    <span class="w-6 h-6 bg-blue-500 rounded-full"></span>
                                @else
                                    <span class="w-6 h-6 bg-gray-400 rounded-full"></span>
                                @endif
                            </div>
                        </div>

                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>

                        <!-- Status Badge -->
                        <div
                            class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold uppercase
                            @if ($user->status === 'active') bg-green-100 text-green-800
                            @elseif($user->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($user->status === 'completed') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if ($user->status === 'active')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Aktif
                            @elseif($user->status === 'pending')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                Menunggu Verifikasi
                            @elseif($user->status === 'completed')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Selesai
                            @else
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                Dibatalkan
                            @endif
                        </div>

                        <!-- Quick Info -->
                        <div class="mt-6 pt-6 border-t border-gray-200 space-y-3 text-left">
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-600">
                                    {{ $user->department->name ?? 'Belum ditentukan' }}
                                </span>
                            </div>
                            @if ($user->start_date && $user->end_date)
                                <div class="flex items-center gap-3 text-sm">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-gray-600">
                                        {{ \Carbon\Carbon::parse($user->start_date)->translatedFormat('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($user->end_date)->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Informasi Personal</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1">Nama Lengkap</label>
                            <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1">Email</label>
                            <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1">No. Telepon</label>
                            <p class="text-gray-900 font-medium">{{ $user->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Education Information -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Informasi Pendidikan</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1">Jenjang Pendidikan</label>
                            <p class="text-gray-900 font-medium">{{ $user->level ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1">Institusi</label>
                            <p class="text-gray-900 font-medium">{{ $user->institution->name ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-500 mb-1">Jurusan</label>
                            <p class="text-gray-900 font-medium">{{ $user->major ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Internship Information -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Informasi Magang</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1">Bagian</label>
                            <p class="text-gray-900 font-medium">{{ $user->department->name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-500 mb-1">Status</label>
                            <span
                                class="inline-flex px-3 py-1 rounded-full text-xs font-semibold uppercase
                                @if ($user->status === 'active') bg-green-100 text-green-800
                                @elseif($user->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($user->status === 'completed') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if ($user->status === 'active')
                                    Aktif
                                @elseif($user->status === 'pending')
                                    Menunggu Verifikasi
                                @elseif($user->status === 'completed')
                                    Selesai
                                @else
                                    Dibatalkan
                                @endif
                            </span>
                        </div>
                        @if ($user->start_date && $user->end_date)
                            <div>
                                <label class="block text-sm font-semibold text-gray-500 mb-1">Tanggal Mulai</label>
                                <p class="text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($user->start_date)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-500 mb-1">Tanggal Selesai</label>
                                <p class="text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($user->end_date)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        @endif
                        @if ($user->acceptance_proof)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-500 mb-2">Bukti Penerimaan</label>
                                <a href="{{ Storage::url($user->acceptance_proof) }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-800 rounded-lg hover:bg-emerald-100 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Lihat Dokumen
                                </a>
                            </div>
                        @endif

                        <!-- Report File Section -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-500 mb-2">Laporan Akhir</label>
                            @if ($user->report_file)
                                <div class="flex items-center gap-3">
                                    <a href="{{ Storage::url($user->report_file) }}" target="_blank"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-lg hover:bg-blue-100 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Lihat Laporan
                                    </a>
                                    {{-- <form action="{{ route('profile.report.delete') }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-800 rounded-lg hover:bg-red-100 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form> --}}
                                </div>
                            @else
                                <button onclick="openModal('reportModal')"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-800 text-white rounded-lg hover:bg-emerald-900 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Upload Laporan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Keamanan Akun</h3>
                    </div>

                    <div class="space-y-4">
                        <button onclick="openModal('passwordModal')"
                            class="w-full flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-800" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-semibold text-gray-900">Ubah Password</p>
                                    <p class="text-sm text-gray-500">Update password akun Anda</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <div class="p-4 rounded-lg border border-gray-200 bg-gray-50">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">Akun Terdaftar</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Akun dibuat pada
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('profile.partials.report-modal')

    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modals = ['passwordModal', 'reportModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    closeModal(modalId);
                }
            });
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = ['passwordModal', 'reportModal'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (!modal.classList.contains('hidden')) {
                        closeModal(modalId);
                    }
                });
            }
        });

        // Auto open modal if there are errors
        @if ($errors->any())
            @if ($errors->has('current_password') || $errors->has('new_password'))
                openModal('passwordModal');
            @elseif ($errors->has('report_file'))
                openModal('reportModal');
            @endif
        @endif
    </script>
</x-app-layout>
