<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $attendances = $user->attendances;

        $hadir = $attendances->where('status', 'present')->count();
        $sakit = $attendances->where('status', 'sick')->count();
        $izin = $attendances->where('status', 'permission')->count();
        $alfa = $attendances->where('status', 'absent')->count();
        $terlambat = $attendances->where('status', 'late')->count();

        // Calculate progress percentage
        $startDate = Carbon::parse($user->start_date);
        $endDate = Carbon::parse($user->end_date);
        $today = Carbon::today();

        // Calculate total working days (excluding weekends)
        $totalWorkingDays = 0;
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            if ($currentDate->isWeekday()) {
                $totalWorkingDays++;
            }
            $currentDate->addDay();
        }

        // Calculate elapsed working days
        $elapsedWorkingDays = 0;
        $currentDate = $startDate->copy();
        while ($currentDate->lte($today) && $currentDate->lte($endDate)) {
            if ($currentDate->isWeekday()) {
                $elapsedWorkingDays++;
            }
            $currentDate->addDay();
        }

        $progressPercentage = $totalWorkingDays > 0 ? round(($elapsedWorkingDays / $totalWorkingDays) * 100) : 0;
        $progressPercentage = min($progressPercentage, 100); // Cap at 100%

        return view('dashboard', [
            'user' => $user,
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alfa' => $alfa,
            'terlambat' => $terlambat,
            'totalWorkingDays' => $totalWorkingDays,
            'elapsedWorkingDays' => $elapsedWorkingDays,
            'progressPercentage' => $progressPercentage,
        ]);
    }
}

