<x-app-layout>
    <div class="max-w-7xl mx-auto pt-6 pb-12 px-3 sm:px-6 lg:px-8">

        <h2 class="text-xl font-semibold mb-6 text-slate-800">
            Foto Absensi
        </h2>

        @forelse ($photos->groupBy(fn($p) => $p['date']->translatedFormat('F Y')) as $month => $items)

            <!-- Header Bulan -->
            <div class="text-xs sm:text-sm font-semibold text-slate-400 uppercase mb-3 mt-8">
                {{ $month }}
            </div>

            <!-- Grid Responsive -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                @foreach ($items as $photo)
                    <div class=" bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-md transition">
                        <img src="{{ asset('storage/' . $photo['path']) }}" loading="lazy"
                            class="w-full h-32 sm:h-36 md:h-40 lg:h-44 object-cover">

                        <div class="px-2 sm:px-3 py-1.5 sm:py-2 text-[10px] sm:text-xs text-slate-500">
                            {{ $photo['date']->translatedFormat('d F Y') }}
                        </div>
                    </div>
                @endforeach
            </div>

        @empty
            <div class="text-center text-slate-400 py-16">
                Belum ada foto absensi
            </div>
        @endforelse

    </div>
</x-app-layout>
