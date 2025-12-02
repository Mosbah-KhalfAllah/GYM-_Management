<?php
// app/Http/Controllers/Member/DashboardController.php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\WorkoutProgram;
use App\Models\ClassModel;
use App\Models\Challenge;
use App\Models\ExerciseLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'attendance_this_week' => Attendance::where('user_id', $user->id)
                ->thisWeek()
                ->count(),
            'attendance_this_month' => Attendance::where('user_id', $user->id)
                ->thisMonth()
                ->count(),
            'active_programs' => $user->programs()->wherePivot('status', 'active')->count(),
            'completed_exercises' => ExerciseLog::whereHas('memberProgram', function ($query) use ($user) {
                $query->where('member_id', $user->id);
            })->where('completed', true)->count(),
            'upcoming_classes' => $user->bookings()
                ->whereHas('class', function ($query) {
                    $query->where('schedule_time', '>', now())
                          ->where('status', 'scheduled');
                })
                ->count(),
            'challenge_points' => $user->challengeParticipants()->sum('points_earned'),
        ];

        // Programme actif
        $activeProgram = $user->programs()->wherePivot('status', 'active')->first();
        $todaysExercises = [];

        if ($activeProgram) {
            $currentDay = $activeProgram->pivot->current_day;
            $todaysExercises = $activeProgram->exercises()
                ->where('day_number', $currentDay)
                ->get();
        }

        // Classes à venir
        $upcomingClasses = $user->bookings()
            ->with('class')
            ->whereHas('class', function ($query) {
                $query->where('schedule_time', '>', now())
                      ->where('status', 'scheduled');
            })
            ->orderBy('created_at')
            ->take(5)
            ->get();

        // Challenges actifs
        $activeChallenges = Challenge::where('is_active', true)
            ->where('end_date', '>=', now())
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('member_id', $user->id);
            })
            ->with(['participants' => function ($query) use ($user) {
                $query->where('member_id', $user->id);
            }])
            ->get();

        // Présences récentes
        $recentAttendances = Attendance::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('member.dashboard', compact(
            'stats',
            'activeProgram',
            'todaysExercises',
            'upcomingClasses',
            'activeChallenges',
            'recentAttendances'
        ));
    }
}