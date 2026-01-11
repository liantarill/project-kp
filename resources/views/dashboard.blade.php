<x-app-layout>
    <x-slot name="header">
        <h1 class="font-bold text-2xl sm:text-3xl text-gray-800 leading-tight">
            {{ 'Selamat Pagi, ' . Str::before(auth()->user()->name, ' ') }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">Berikut ringkasan aktivitas magang Anda hari ini</p>
    </x-slot>

    <!-- Profile Section -->
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-5 sm:p-6">
                    <div class="flex items-start gap-4">
                        <!-- Avatar -->
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-green-800 to-green-900 rounded-full flex items-center justify-center text-white text-xl sm:text-2xl font-bold flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <h2 class="font-bold text-lg sm:text-xl text-gray-900">
                                    {{ $user->name }}
                                </h2>
                                <span
                                    class="px-3 py-1 flex bg-green-50 text-green-700 text-xs font-medium rounded-full whitespace-nowrap uppercase
                                     class="mt-4
                                    inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold
                                    @if ($user->status === 'active') bg-green-100 text-green-800
                            @elseif($user->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($user->status === 'completed') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif"">
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
                                </span>
                            </div>

                            <div class="text-sm text-primary font-medium mb-1">
                                {{ $user->department->name }}
                            </div>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    {{ $user->institution->name }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $user->start_date->translatedFormat('M d') }} -
                                    {{ $user->end_date->translatedFormat('M d, Y') }}
                                </span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4">

                <div
                    class="bg-white shadow-sm rounded-xl p-4 sm:p-5 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="text-xs text-gray-500 mb-1">Hadir</div>
                    <div class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ $hadir }}</div>
                    <div class="text-xs font-medium text-primary">Hari</div>
                </div>

                <div
                    class="bg-white shadow-sm rounded-xl p-4 sm:p-5 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="text-xs text-gray-500 mb-1">Sakit</div>
                    <div class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ $sakit }}</div>
                    <div class="text-xs font-medium text-gray-500">Hari</div>
                </div>

                <div
                    class="bg-white shadow-sm rounded-xl p-4 sm:p-5 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="text-xs text-gray-500 mb-1">Izin</div>
                    <div class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ $izin }}</div>
                    <div class="text-xs font-medium text-gray-500">Hari</div>
                </div>

                <div
                    class="bg-white shadow-sm rounded-xl p-4 sm:p-5 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="text-xs text-gray-500 mb-1">Terlambat</div>
                    <div class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ $terlambat }}</div>
                    <div class="text-xs font-medium text-gray-500">Hari</div>
                </div>
                <div
                    class="bg-white shadow-sm rounded-xl p-4 sm:p-5 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="text-xs text-gray-500 mb-1">Alfa</div>
                    <div class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ $alfa }}</div>
                    <div class="text-xs font-medium text-gray-500">Hari</div>
                </div>

            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="py-4 pb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-5 sm:p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-gray-900 text-base sm:text-lg">
                                Durasi Magang
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Progress berdasarkan hari kerja
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl sm:text-3xl font-bold text-primary">
                                {{ $progressPercentage }}%
                            </div>
                            <div class="text-xs text-gray-500">Completed</div>
                        </div>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-primary h-2.5 rounded-full transition-all duration-500"
                            style="width: {{ $progressPercentage }}%">
                        </div>
                    </div>

                    <div class="mt-3 flex justify-between items-center text-xs">
                        <span class="text-gray-600">
                            <span class="font-semibold text-gray-900">Mulai:</span>
                            {{ $user->start_date->translatedFormat('d M Y') }}
                        </span>
                        <span class="text-gray-600 font-medium">
                            {{ $totalWorkingDays - $elapsedWorkingDays }} Hari Tersisa
                        </span>
                        <span class="text-gray-600 text-end">
                            <span class="font-semibold text-gray-900">Selesai:</span>
                            {{ $user->end_date->translatedFormat('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
