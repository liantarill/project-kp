<x-app-layout>
    <script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">

    <div class="max-w-4xl mx-auto mt-6 px-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold mb-4 ">Riwayat Absensi</h2>
            <a href="{{ route('absensi.export', auth()->id()) }}"
                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                Download
            </a>
        </div>
        <table class="w-full border-collapse border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">Tanggal</th>
                    <th class="border p-2">Jam</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Bukti</th>
                    <th class="border p-2">Aksi</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $item)
                    <tr>
                        <td class="border p-2">
                            {{ $item->created_at->locale('id')->translatedFormat('d F Y') }}
                        </td>
                        <td class="border p-2">
                            {{ $item->created_at->format('H:i') }}
                        </td>
                        <td class="border p-2">
                            @switch($item->status)
                                @case('present')
                                    Hadir
                                @break

                                @case('sick')
                                    Sakit
                                @break

                                @case('permission')
                                    Izin
                                @break

                                @case('absent')
                                    Alfa
                                @break

                                @case('late')
                                    Terlambat
                                @break

                                @default
                            @endswitch
                        </td>
                        <td class="border p-2">
                            <a href="{{ asset('storage/' . $item->photo) }}" target="_blank"
                                class="text-blue-600 underline">
                                Lihat Foto
                            </a>

                        </td>
                        <td class="border p-2">
                            @if ($item->latitude)
                                <a href="javascript:void(0)" class="text-blue-600 underline"
                                    onclick="openAttendancePopup({{ $item }})">
                                    Lihat
                                </a>
                            @endif

                        </td>
                        </td>

                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border p-3 text-center text-gray-500">
                                Belum ada data absensi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @include('components.attendance-detail')
        </div>


    </x-app-layout>
