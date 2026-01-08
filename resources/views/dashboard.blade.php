<x-app-layout>
    <x-slot name="header">
        <h1 class="font-extrabold text-3xl text-gray-800  leading-tight">
            {{ 'Selamat Datang, ' . Str::before(auth()->user()->name, ' ') }}
        </h1>
        <p>Berikut ringkasan aktivitas magang Anda hari ini</p>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex">
                    <div>
                        <h2>
                            {{ $user->name }}
                        </h2>
                        <div>
                            {{ $user->department->name }}
                        </div>
                        <div class="block md:flex  gap-11">
                            {{ $user->institution->name }}
                            <div>
                                {{ $user->start_date->translatedFormat('d F Y') }}
                                -
                                {{ $user->end_date->translatedFormat('d F Y') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-4">
        <div
            class="max-w-7xl mx-auto sm:px-6 lg:px-8
                grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="text-sm text-gray-600 mb-1">Hadir</div>
                <div class="text-2xl font-bold text-gray-900">{{ $hadir }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="text-sm text-gray-600 mb-1">Sakit</div>
                <div class="text-2xl font-bold text-gray-900">{{ $sakit }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="text-sm text-gray-600 mb-1">Izin</div>
                <div class="text-2xl font-bold text-gray-900">{{ $izin }}</div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="text-sm text-gray-600 mb-1">Alfa</div>
                <div class="text-2xl font-bold text-gray-900">{{ $alfa }}</div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="text-sm text-gray-600 mb-1">Terlambat</div>
                <div class="text-2xl font-bold text-gray-900">{{ $terlambat }}</div>
            </div>

        </div>
    </div>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">
                                Durasi Magang
                            </h3>
                            <p class="text-sm text-gray-600">
                                Progress berdasarkan hari kerja
                            </p>
                        </div>
                        <div class="text-2xl font-bold">
                            {{ $progressPercentage }}%
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        {{ $elapsedWorkingDays }} dari {{ $totalWorkingDays }} hari kerja
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
