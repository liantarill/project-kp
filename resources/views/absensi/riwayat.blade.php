<x-app-layout>
    <script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">

    <div class="max-w-4xl mx-auto mt-6 px-6">

        <h2 class="text-xl font-bold mb-4">Riwayat Absensi</h2>
        <a href="{{ route('absensi.export', auth()->id()) }}">download</a>

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
                                    Telat
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
            <div id="attendance-popup" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
                <div class="bg-white w-2/3 rounded-lg p-4 relative">
                    <button onclick="closeAttendancePopup()" class="absolute top-2 right-2 text-xl">✕</button>

                    <h2 class="text-lg font-semibold mb-3">Detail Attendance</h2>

                    <div class="space-y-1 mb-4">
                        <p><strong>Tanggal:</strong> <span id="popup-date"></span></p>
                        <p><strong>Nama:</strong> <span id="popup-name"></span></p>
                        <p><strong>Lokasi:</strong>
                            <span id="popup-lat"></span>,
                            <span id="popup-lng"></span>
                        </p>
                    </div>

                    @include('components.attendance-detail')
                </div>
            </div>

        </div>


    </x-app-layout>
