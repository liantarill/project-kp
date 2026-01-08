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
                            {{ auth()->user()->name }}
                        </h2>
                        <div>
                            {{ auth()->user()->department->name }}
                        </div>
                        <div class="block md:flex  gap-11">
                            {{ auth()->user()->institution->name }}
                            <div>
                                {{ auth()->user()->start_date->translatedFormat('d F Y') }}
                                -
                                {{ auth()->user()->end_date->translatedFormat('d F Y') }}
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

            <div class="bg-white overflow-hidden shadow-sm rounded-lg  p-6 ">
                Hadir
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                Sakit
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                Izin
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                Alfa
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                Terlambat
            </div>

        </div>
    </div>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between p-6 text-gray-900 ">
                    <div>
                        <h3>
                            Durasi Magang
                        </h3>
                        <p>
                            Progress berdasarkan hari kerja
                        </p>
                    </div>
                    <div>
                        20%
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
