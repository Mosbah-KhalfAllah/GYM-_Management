<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Display the member's profile.
     */
    public function index()
    {
        $user = Auth::user();
        $user->load(['membership', 'programs']);
        
        // Statistiques du profil
        $stats = [
            'total_workouts' => \App\Models\Attendance::where('user_id', $user->id)->count(),
            'total_exercises' => \App\Models\ExerciseLog::whereHas('memberProgram', function ($query) use ($user) {
                    $query->where('member_id', $user->id);
                })->where('completed', true)->count(),
            'total_classes' => \App\Models\ClassBooking::where('member_id', $user->id)->count(),
            'challenge_points' => \App\Models\ChallengeParticipant::where('member_id', $user->id)->sum('points_earned'),
        ];
        
        // Progression récente
        $recentProgress = \App\Models\ExerciseLog::whereHas('memberProgram', function ($query) use ($user) {
                $query->where('member_id', $user->id);
            })
            ->with('exercise')
            ->where('completed', true)
            ->latest()
            ->take(5)
            ->get();
        
        return view('member.profile.index', compact('user', 'stats', 'recentProgress'));
    }

    /**
     * Update the member's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);
        
        // Gestion de l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
            
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        
        $user->update($validated);
        
        return redirect()->route('member.profile.index')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Update the member's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->route('member.profile.index')
            ->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Update membership preferences.
     */
    public function updateMembership(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->membership) {
            return redirect()->route('member.profile.index')
                ->with('error', 'Aucun abonnement trouvé.');
        }
        
        $validated = $request->validate([
            'auto_renewal' => ['boolean'],
        ]);
        
        $user->membership->update([
            'auto_renewal' => $request->has('auto_renewal'),
        ]);
        
        return redirect()->route('member.profile.index')
            ->with('success', 'Préférences d\'abonnement mises à jour.');
    }
}