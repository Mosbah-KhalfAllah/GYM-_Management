<?php
// app/Http\Controllers/Coach/DashboardController.php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkoutProgram;
use App\Models\ClassModel;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $coach = Auth::user();

        $stats = [
            'total_programs' => $coach->createdPrograms()->count(),
            'active_programs' => $coach->createdPrograms()->where('is_active', true)->count(),
            'total_classes' => $coach->classes()->count(),
            'upcoming_classes' => $coach->classes()
                ->where('schedule_time', '>', now())
                ->where('status', 'scheduled')
                ->count(),
            'assigned_members' => User::whereHas('programs', function ($query) use ($coach) {
                $query->where('coach_id', $coach->id);
            })->count(),
        ];

        // Classes à venir
        $upcomingClasses = $coach->classes()
            ->withCount('bookings')
            ->where('schedule_time', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('schedule_time')
            ->take(5)
            ->get();

        // Membres assignés récents
        $assignedMembers = User::whereHas('programs', function ($query) use ($coach) {
            $query->where('coach_id', $coach->id);
        })
        ->with(['membership', 'programs'])
        ->latest()
        ->take(5)
        ->get();

        // Présences récentes
        $recentAttendances = Attendance::whereHas('user.programs', function ($query) use ($coach) {
            $query->where('coach_id', $coach->id);
        })
        ->with('user')
        ->latest()
        ->take(10)
        ->get();

        return view('coach.dashboard', compact(
            'stats',
            'upcomingClasses',
            'assignedMembers',
            'recentAttendances'
        ));
    }
}