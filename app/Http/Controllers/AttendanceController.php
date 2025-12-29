<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'check_in' => ['required'],
            'status' => ['required', 'in:present,permission,sick,absent,late'],
            'note' => ['nullable'],
            'photo' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

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

        Attendance::create([
            'date' => $request->date,
            'check_in' => $request->check_in,
            'user_id' => Auth::id(),
            'status' => $request->status,
            'note' => $request->note,
            'photo' => $request->photo,
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
