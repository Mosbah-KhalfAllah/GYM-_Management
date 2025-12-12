<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\WorkoutProgram;
use App\Models\User;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display the coach's schedule.
     */
    public function index()
    {
        $coachId = auth()->id();
        
        // Cours à venir
        $upcomingClasses = ClassModel::where('coach_id', $coachId)
            ->where('schedule_time', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('schedule_time')
            ->get();
        
        // Cours aujourd'hui
        $todayClasses = ClassModel::where('coach_id', $coachId)
            ->whereDate('schedule_time', today())
            ->where('status', 'scheduled')
            ->orderBy('schedule_time')
            ->get();
        
        // Programmes actifs
        $activePrograms = WorkoutProgram::where('coach_id', $coachId)
            ->where('is_active', true)
            ->withCount('members')
            ->get();
        
        // Statistiques
        $stats = [
            'today_classes' => $todayClasses->count(),
            'upcoming_classes' => $upcomingClasses->count(),
            'active_programs' => $activePrograms->count(),
            'total_members' => User::whereHas('programs', function ($query) use ($coachId) {
                $query->where('coach_id', $coachId);
            })->count(),
        ];
        
        // Calendrier des 7 prochains jours
        $calendarDays = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($i);
            $dayClasses = ClassModel::where('coach_id', $coachId)
                ->whereDate('schedule_time', $date)
                ->where('status', 'scheduled')
                ->get();
            
            $calendarDays[] = [
                'date' => $date,
                'day_name' => $date->locale('fr')->dayName,
                'day_number' => $date->day,
                'classes' => $dayClasses,
                'class_count' => $dayClasses->count(),
            ];
        }
        
        return view('coach.schedule.index', compact(
            'upcomingClasses',
            'todayClasses',
            'activePrograms',
            'stats',
            'calendarDays'
        ));
    }
}