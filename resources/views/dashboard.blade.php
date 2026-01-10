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
                                    class="px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full whitespace-nowrap uppercase">
                                    {{ $user->status }}
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
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">

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
                    <div class="text-xs text-gray-500 mb-1">Terlambat</div>
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
                            <span class="font-semibold text-gray-900">Start:</span>
                            {{ $user->start_date->translatedFormat('M d') }}
                        </span>
                        <span class="text-gray-600 font-medium">
                            {{ $totalWorkingDays - $elapsedWorkingDays }} Days Remaining
                        </span>
                        <span class="text-gray-600">
                            <span class="font-semibold text-gray-900">End:</span>
                            {{ $user->end_date->translatedFormat('M d') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
