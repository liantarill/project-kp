<x-app-layout>

    <div class="max-w-4xl mx-auto mt-6">

        <h2 class="text-xl font-bold mb-4">Riwayat Absensi</h2>

        <table class="w-full border-collapse border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">Tanggal</th>
                    <th class="border p-2">Jam</th>
                    <th class="border p-2">Latitude</th>
                    <th class="border p-2">Longitude</th>
                    <th class="border p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $item)
                    <tr>
                        <td class="border p-2">
                            {{ $item->created_at->format('d-m-Y') }}
                        </td>
                        <td class="border p-2">
                            {{ $item->created_at->format('H:i:s') }}
                        </td>
                        <td class="border p-2">{{ $item->latitude }}</td>
                        <td class="border p-2">{{ $item->longitude }}</td>
                        <td class="border p-2">{{ $item->status }}</td>

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

    </div>

</x-app-layout>
