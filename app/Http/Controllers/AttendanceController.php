<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExcelExport;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Maatwebsite\Excel\Facades\Excel;

use function Symfony\Component\Clock\now;

class AttendanceController extends Controller
{
    private float $officeLat = -5.3851721;

    private float $officeLng = 105.2605921;

    private int $maxRadius = 100;

    public function index()
    {
        return view('absensi.index');
    }

    public function history()
    {
        $attendances = Attendance::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // $attendances = Attendance::where('user_id', auth()->id())
        //     ->orderBy('created_at', 'desc')
        //     ->get()
        //     ->groupBy(function ($item) {
        //         return $item->created_at->translatedFormat('F Y');
        //     });

        return view('absensi.riwayat', compact('attendances'));
    }

    public function export()
    {
        // return Excel::download(new AttendanceExcelExport, 'invoices.xlsx');

        return Excel::download(
            new AttendanceExcelExport(Auth::id()),
            'Rekap Absensi-' . Auth::user()->name . '.xlsx'
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'status' => ['required', 'in:present,permission,sick,absent,late'],
                'note' => ['required_if:status,permission,sick', 'nullable', 'string'],
                'latitude' => ['required_if:status,present'],
                'longitude' => ['required_if:status,present'],
            ],
            [
                'note.required_if' => 'Catatan wajib diisi jika status izin atau sakit.',
                'latitude.required_if' => 'Lokasi tidak terbaca.',
                'longitude.required_if' => 'Lokasi tidak terbaca.',
            ]
        );

        $attendanceStatus = $request->status;
        $fileName = null;

        // berlaku jika hadir saja
        if ($request->status === 'present') {
            $distance = $this->distance(
                $this->officeLat,
                $this->officeLng,
                $request->latitude,
                $request->longitude
            );

            if ($distance > $this->maxRadius) {
                return back()->withErrors([
                    'location' => 'Anda berada di luar area kantor (' . round($distance) . ' meter)',
                ]);
            }

            $image = $request->photo;
            // 1. Bersihkan base64
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);

            $userName = Str::slug(Auth::user()->name, '_');
            $dateTime = now()->format('Y-m-d_H-i-s');
            $fileName = "attendance/{$userName}/{$userName}_{$dateTime}.jpg";

            $manager = new ImageManager(new Driver);
            $compressedImage = $manager
                ->read($imageData)
                ->toJpeg(75); // q

            Storage::disk('public')->put($fileName, (string) $compressedImage);

            // batas jam absen
            $batasJam = '08:00:00';

            // kalau mau absen hadir tapi terlambat nanti dia jadi terlambat
            if (now()->format('H:i:s') > $batasJam) {
                $attendanceStatus = 'late';
            }
        }

        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'date' => today(),
            ],
            [
                'check_in' => now()->format('H:i:s'),
                'status' => $attendanceStatus,
                'note' => $request->note,
                'photo' => $fileName,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ],
        );

        if (! $attendance->wasRecentlyCreated) {
            if ($fileName) {
                Storage::disk('public')->delete($fileName);
            }

            return back()->withErrors(['attendance' => 'Anda sudah absen hari ini.']);
        }

        return redirect()->back()->with('success', 'absensi berhasil');
    }

    private function distance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    public function photos()
    {
        $user = Auth::user();

        // Ambil semua attendance user yang ada foto
        $attendances = Attendance::where('user_id', $user->id)
            ->whereNotNull('photo')
            ->get();

        // $photos = $attendances->map(function ($attendance) {
        //     $file = $attendance->photo; // asumsikan ini path relatif di storage 'public'
        //     $fullPath = "attendance/{$file}";

        //     if (! Storage::disk('public')->exists($fullPath)) {
        //         return null; // skip jika file tidak ada
        //     }

        //     // Ambil tanggal dari nama file atau dari created_at attendance
        //     preg_match('/\d{4}-\d{2}-\d{2}/', $file, $match);

        //     $date = $match
        //         ? Carbon::parse($match[0])
        //         : $attendance->created_at;

        //     return [
        //         'path' => $fullPath,
        //         'date' => $date,
        //     ];
        // })
        //     ->filter() // hapus yang null karena filenya tidak ada
        //     ->sortByDesc('date');

        return view('absensi.foto', compact('attendances'));
    }
}
