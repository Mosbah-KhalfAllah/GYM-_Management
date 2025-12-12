<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProgressController extends Controller
{
    /**
     * Display progress overview.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Statistiques principales
        $stats = $this->getProgressStats($user);
        
        // Graphique des présences (30 derniers jours)
        $attendanceChart = $this->getAttendanceChartData($user);
        
        // Graphique des exercices (30 derniers jours)
        $exerciseChart = $this->getExerciseChartData($user);
        
        // Graphique des poids (si disponible)
        $weightChart = $this->getWeightChartData($user);
        
        // Objectifs
        $goals = $this->getUserGoals($user);
        
        // Progression des programmes
        $programProgress = $this->getProgramProgress($user);
        
        return view('member.progress.index', compact(
            'stats',
            'attendanceChart',
            'exerciseChart',
            'weightChart',
            'goals',
            'programProgress'
        ));
    }

    /**
     * Get progress chart data via AJAX.
     */
    public function chart(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'attendance');
        $period = $request->get('period', 'month');
        
        switch ($type) {
            case 'attendance':
                $data = $this->getAttendanceChartData($user, $period);
                break;
                
            case 'exercises':
                $data = $this->getExerciseChartData($user, $period);
                break;
                
            case 'weight':
                $data = $this->getWeightChartData($user, $period);
                break;
                
            default:
                $data = [];
        }
        
        return response()->json($data);
    }

    /**
     * Get progress statistics.
     */
    private function getProgressStats($user)
    {
        $startOfMonth = now()->startOfMonth();
        $startOfYear = now()->startOfYear();
        
        return [
            'monthly_attendance' => \App\Models\Attendance::where('user_id', $user->id)
                ->where('check_in', '>=', $startOfMonth)
                ->count(),
            
            'yearly_attendance' => \App\Models\Attendance::where('user_id', $user->id)
                ->where('check_in', '>=', $startOfYear)
                ->count(),
            
            'total_workout_hours' => \App\Models\Attendance::where('user_id', $user->id)
                ->whereNotNull('duration_minutes')
                ->sum('duration_minutes') / 60,
            
            'exercises_completed' => \App\Models\ExerciseLog::whereHas('memberProgram', function ($query) use ($user) {
                    $query->where('member_id', $user->id);
                })->where('completed', true)->count(),
            
            'programs_completed' => \App\Models\MemberProgram::where('member_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            
            'challenges_won' => \App\Models\ChallengeParticipant::where('member_id', $user->id)
                ->where('completed', true)
                ->count(),
        ];
    }

    /**
     * Get attendance chart data.
     */
    private function getAttendanceChartData($user, $period = 'month')
    {
        $data = [];
        $now = Carbon::now();
        
        switch ($period) {
            case 'week':
                $days = 7;
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $count = \App\Models\Attendance::where('user_id', $user->id)
                        ->whereDate('check_in', $date)
                        ->count();
                    
                    $data[] = [
                        'label' => $date->format('D'),
                        'value' => $count,
                        'date' => $date->format('Y-m-d'),
                    ];
                }
                break;
                
            case 'month':
                $days = 30;
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $count = \App\Models\Attendance::where('user_id', $user->id)
                        ->whereDate('check_in', $date)
                        ->count();
                    
                    $data[] = [
                        'label' => $date->format('d/m'),
                        'value' => $count,
                        'date' => $date->format('Y-m-d'),
                    ];
                }
                break;
                
            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $start = $date->copy()->startOfMonth();
                    $end = $date->copy()->endOfMonth();
                    
                    $count = \App\Models\Attendance::where('user_id', $user->id)
                        ->whereBetween('check_in', [$start, $end])
                        ->count();
                    
                    $data[] = [
                        'label' => $date->format('M'),
                        'value' => $count,
                        'date' => $date->format('Y-m'),
                    ];
                }
                break;
        }
        
        return $data;
    }

    /**
     * Get exercise chart data.
     */
    private function getExerciseChartData($user, $period = 'month')
    {
        $data = [];
        $now = Carbon::now();
        
        switch ($period) {
            case 'week':
                $days = 7;
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $count = \App\Models\ExerciseLog::whereHas('memberProgram', function ($query) use ($user) {
                            $query->where('member_id', $user->id);
                        })
                        ->whereDate('workout_date', $date)
                        ->where('completed', true)
                        ->count();
                    
                    $data[] = [
                        'label' => $date->format('D'),
                        'value' => $count,
                        'date' => $date->format('Y-m-d'),
                    ];
                }
                break;
                
            case 'month':
                $days = 30;
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $count = \App\Models\ExerciseLog::whereHas('memberProgram', function ($query) use ($user) {
                            $query->where('member_id', $user->id);
                        })
                        ->whereDate('workout_date', $date)
                        ->where('completed', true)
                        ->count();
                    
                    $data[] = [
                        'label' => $date->format('d/m'),
                        'value' => $count,
                        'date' => $date->format('Y-m-d'),
                    ];
                }
                break;
        }
        
        return $data;
    }

    /**
     * Get weight chart data.
     */
    private function getWeightChartData($user, $period = 'month')
    {
        // Cette méthode suppose que vous avez une table pour suivre le poids
        // Pour l'instant, retourne des données fictives
        $data = [];
        $now = Carbon::now();
        
        // Données fictives pour la démonstration
        $weights = [75, 74.5, 74, 73.5, 73, 72.8, 72.5, 72.3, 72, 71.8];
        
        for ($i = count($weights) - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i * 3);
            $data[] = [
                'label' => $date->format('d/m'),
                'value' => $weights[$i],
                'date' => $date->format('Y-m-d'),
            ];
        }
        
        return $data;
    }

    /**
     * Get user goals.
     */
    private function getUserGoals($user)
    {
        return [
            [
                'title' => '3 séances par semaine',
                'current' => \App\Models\Attendance::where('user_id', $user->id)
                    ->whereBetween('check_in', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count(),
                'target' => 3,
                'type' => 'attendance',
            ],
            [
                'title' => '50 exercices ce mois',
                'current' => \App\Models\ExerciseLog::whereHas('memberProgram', function ($query) use ($user) {
                        $query->where('member_id', $user->id);
                    })
                    ->whereMonth('workout_date', now()->month)
                    ->where('completed', true)
                    ->count(),
                'target' => 50,
                'type' => 'exercises',
            ],
            [
                'title' => 'Terminer le programme actuel',
                'current' => $user->activeProgram ? $user->activeProgram->pivot->completion_percentage : 0,
                'target' => 100,
                'type' => 'program',
            ],
        ];
    }

    /**
     * Get program progress.
     */
    private function getProgramProgress($user)
    {
        return $user->programs()
            ->withPivot(['start_date', 'end_date', 'completion_percentage', 'status'])
            ->orderByPivot('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($program) {
                return [
                    'title' => $program->title,
                    'start_date' => is_string($program->pivot->start_date) 
                        ? \Carbon\Carbon::parse($program->pivot->start_date)->format('d/m/Y')
                        : $program->pivot->start_date->format('d/m/Y'),
                    'end_date' => is_string($program->pivot->end_date)
                        ? \Carbon\Carbon::parse($program->pivot->end_date)->format('d/m/Y')
                        : $program->pivot->end_date->format('d/m/Y'),
                    'progress' => $program->pivot->completion_percentage,
                    'status' => $program->pivot->status,
                    'duration' => $program->duration_days,
                    'current_day' => $program->pivot->current_day,
                ];
            });
    }
}