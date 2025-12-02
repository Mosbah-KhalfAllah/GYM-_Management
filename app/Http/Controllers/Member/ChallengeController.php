<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    /**
     * Display a listing of available challenges.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Challenges actifs disponibles
        $availableChallenges = Challenge::where('is_active', true)
            ->where('end_date', '>=', now())
            ->whereDoesntHave('participants', function ($query) use ($user) {
                $query->where('member_id', $user->id);
            })
            ->latest()
            ->get();
        
        // Challenges en cours
        $activeChallenges = Challenge::where('is_active', true)
            ->where('end_date', '>=', now())
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('member_id', $user->id)
                    ->where('completed', false);
            })
            ->with(['participants' => function ($query) use ($user) {
                $query->where('member_id', $user->id);
            }])
            ->latest()
            ->get();
        
        // Challenges terminés
        $completedChallenges = Challenge::whereHas('participants', function ($query) use ($user) {
                $query->where('member_id', $user->id)
                    ->where('completed', true);
            })
            ->with(['participants' => function ($query) use ($user) {
                $query->where('member_id', $user->id);
            }])
            ->latest()
            ->paginate(10);
        
        // Classement global
        $leaderboard = ChallengeParticipant::with('member')
            ->selectRaw('member_id, SUM(points_earned) as total_points')
            ->groupBy('member_id')
            ->orderByDesc('total_points')
            ->limit(10)
            ->get();
        
        // Points totaux de l'utilisateur
        $userPoints = ChallengeParticipant::where('member_id', $user->id)
            ->sum('points_earned');
        
        return view('member.challenges.index', compact(
            'availableChallenges',
            'activeChallenges',
            'completedChallenges',
            'leaderboard',
            'userPoints'
        ));
    }

    /**
     * Display the specified challenge.
     */
    public function show(Challenge $challenge)
    {
        $user = Auth::user();
        
        $participation = $challenge->participants()
            ->where('member_id', $user->id)
            ->first();
        
        // Calculer la progression en fonction du type de challenge
        $progress = $this->calculateProgress($user, $challenge);
        
        // Classement pour ce challenge
        $challengeLeaderboard = ChallengeParticipant::where('challenge_id', $challenge->id)
            ->with('member')
            ->orderByDesc('current_progress')
            ->limit(20)
            ->get();
        
        return view('member.challenges.show', compact(
            'challenge',
            'participation',
            'progress',
            'challengeLeaderboard'
        ));
    }

    /**
     * Join a challenge.
     */
    public function join(Request $request, Challenge $challenge)
    {
        $user = Auth::user();
        
        // Vérifier si le challenge est actif
        if (!$challenge->is_active || $challenge->end_date < now()) {
            return redirect()->route('member.challenges.index')
                ->with('error', 'Ce challenge n\'est plus disponible.');
        }
        
        // Vérifier si déjà inscrit
        $existingParticipation = ChallengeParticipant::where('challenge_id', $challenge->id)
            ->where('member_id', $user->id)
            ->first();
        
        if ($existingParticipation) {
            return redirect()->route('member.challenges.show', $challenge)
                ->with('error', 'Vous êtes déjà inscrit à ce challenge.');
        }
        
        // Calculer la progression initiale
        $initialProgress = $this->calculateInitialProgress($user, $challenge);
        
        // S'inscrire
        ChallengeParticipant::create([
            'challenge_id' => $challenge->id,
            'member_id' => $user->id,
            'current_progress' => $initialProgress,
            'points_earned' => 0,
            'completed' => false,
        ]);
        
        return redirect()->route('member.challenges.show', $challenge)
            ->with('success', 'Vous êtes maintenant inscrit au challenge!');
    }

    /**
     * Calculate initial progress for a challenge.
     */
    private function calculateInitialProgress($user, $challenge)
    {
        $startDate = $challenge->start_date;
        
        switch ($challenge->type) {
            case 'attendance':
                // Nombre de présences depuis le début du challenge
                return \App\Models\Attendance::where('user_id', $user->id)
                    ->whereDate('check_in', '>=', $startDate)
                    ->count();
                    
            case 'workout':
                // Nombre d'exercices complétés depuis le début du challenge
                return \App\Models\ExerciseLog::whereHas('memberProgram', function ($query) use ($user) {
                        $query->where('member_id', $user->id);
                    })
                    ->where('workout_date', '>=', $startDate)
                    ->where('completed', true)
                    ->count();
                    
            case 'class':
                // Nombre de cours suivis depuis le début du challenge
                return \App\Models\ClassBooking::where('member_id', $user->id)
                    ->whereHas('class', function ($query) use ($startDate) {
                        $query->where('schedule_time', '>=', $startDate);
                    })
                    ->where('attended', true)
                    ->count();
                    
            default:
                return 0;
        }
    }

    /**
     * Calculate current progress for a challenge.
     */
    private function calculateProgress($user, $challenge)
    {
        $participation = $challenge->participants()
            ->where('member_id', $user->id)
            ->first();
        
        if (!$participation) {
            return [
                'current' => 0,
                'target' => $challenge->target_value,
                'percentage' => 0,
                'remaining' => $challenge->target_value,
            ];
        }
        
        $percentage = ($participation->current_progress / $challenge->target_value) * 100;
        
        return [
            'current' => $participation->current_progress,
            'target' => $challenge->target_value,
            'percentage' => min($percentage, 100),
            'remaining' => max($challenge->target_value - $participation->current_progress, 0),
        ];
    }
}