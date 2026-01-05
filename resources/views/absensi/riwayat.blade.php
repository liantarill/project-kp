<x-app-layout>
    <script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">

    <div class="min-h-screen bg-[#F8FAFC] pb-32">
        <main class="pt-20 px-5 max-w-4xl mx-auto space-y-8">

            <!-- Page Header -->
            <div class="flex flex-col items-center justify-center py-2 space-y-1 mb-6">
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[11px] font-semibold tracking-wide uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    Riwayat Kehadiran
                </span>
                <h2 class="text-2xl font-semibold text-slate-900 tracking-tight">Riwayat Absensi</h2>
                <p class="text-sm text-slate-400">Lihat seluruh data kehadiran Anda</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-center">
                <a href="{{ route('absensi.photos') }}"
                    class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-all shadow-sm text-sm font-medium">
                    <span class="material-symbols-outlined text-[18px]">photo_library</span>
                    <span>Lihat Foto Absen</span>
                </a>
                <a href="{{ route('absensi.export', auth()->id()) }}"
                    class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-sm text-sm font-medium">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    <span>Download</span>
                </a>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Jam</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Bukti</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($attendances as $item)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-700 font-medium">
                                        {{ $item->created_at->locale('id')->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $item->created_at->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @switch($item->status)
                                            @case('present')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Hadir
                                                </span>
                                            @break

                                            @case('sick')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-50 text-orange-700 text-xs font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                                    Sakit
                                                </span>
                                            @break

                                            @case('permission')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                    Izin
                                                </span>
                                            @break

                                            @case('absent')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                    Alfa
                                                </span>
                                            @break

                                            @case('late')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                                    Terlambat
                                                </span>
                                            @break

                                            @default
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-700 text-xs font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                                    -
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($item->photo)
                                            <a href="{{ asset('storage/' . $item->photo) }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors">
                                                <span class="material-symbols-outlined text-[16px]">image</span>
                                                <span>Lihat Foto</span>
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($item->latitude)
                                            <button onclick="openAttendancePopup({{ $item }})"
                                                class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors">
                                                <span class="material-symbols-outlined text-[16px]">location_on</span>
                                                <span>Lihat</span>
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-3">
                                                <div
                                                    class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                                    <span
                                                        class="material-symbols-outlined text-slate-400 text-[32px]">event_busy</span>
                                                </div>
                                                <p class="text-sm text-slate-500 font-medium">Belum ada data absensi</p>
                                                <p class="text-xs text-slate-400">Data kehadiran Anda akan muncul di sini
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>

        @include('components.attendance-detail')
    </x-app-layout>
