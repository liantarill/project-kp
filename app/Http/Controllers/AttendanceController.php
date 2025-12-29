<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $attendances = Attendance::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('absensi.riwayat', compact('attendances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'check_in' => ['nullable'],
            'status' => ['required', 'in:present,permission,sick,absent,late'],
            'note' => ['nullable'],
            'photo' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

        // logic untuk mengukur jarak
        $distance = $this->distance(
            $this->officeLat,
            $this->officeLng,
            $request->latitude,
            $request->longitude
        );

        if ($distance > $this->maxRadius) {
            return back()->withErrors([
                'location' => 'Anda berada di luar area kantor ('.round($distance).' meter)',
            ]);
        }

        $image = $request->photo;
        // bersihkan base64
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);
        // nama user (aman)
        $userName = Str::slug(Auth::user()->name, '_');
        // tanggal & jam (WIB)
        $dateTime = now()->setTimezone('Asia/Jakarta')->format('Y-m-d_H-i-s');
        // nama file
        $fileName = "attendance/{$userName}_{$dateTime}.jpg";
        // simpan
        Storage::disk('public')->put($fileName, $imageData);

        // batas jam absen
        $batasJam = '08:00:00';
        $attendanceStatus = $request->status;

        // kalau mau absen hadir tapi terlambat nanti dia jadi terlambat
        if ($request->status === 'present' && now()->timezone('Asia/Jakarta')->format('H:i:s') > $batasJam) {
            $attendanceStatus = 'late';
        }

        Attendance::create([
            'date' => $request->date,
            'check_in' => $request->check_in,
            'user_id' => Auth::id(),
            'status' => $attendanceStatus,
            'note' => $request->note,
            'photo' => $fileName,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

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
}
