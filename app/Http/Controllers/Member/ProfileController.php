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
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:' . now()->subYears(10)->toDateString(), 'after:1920-01-01'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'avatar' => ['nullable', 'image', 'max:10240'],
        ], [
            'first_name.required' => 'Le prénom est requis.',
            'first_name.regex' => 'Le prénom ne doit contenir que des lettres, espaces, tirets et apostrophes.',
            'first_name.max' => 'Le prénom ne doit pas dépasser 255 caractères.',
            'last_name.required' => 'Le nom est requis.',
            'last_name.regex' => 'Le nom ne doit contenir que des lettres, espaces, tirets et apostrophes.',
            'last_name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'phone.regex' => 'Le téléphone doit contenir uniquement des chiffres et caractères spéciaux (+, -, parenthèses).',
            'phone.max' => 'Le téléphone ne doit pas dépasser 20 caractères.',
            'birth_date.date' => 'La date de naissance doit être une date valide.',
            'birth_date.before_or_equal' => 'L\'âge minimum requis est 10 ans.',
            'birth_date.after' => 'La date de naissance doit être après 1920.',
            'address.max' => 'L\'adresse ne doit pas dépasser 500 caractères.',
            'emergency_contact.regex' => 'Le numéro d\'urgence doit contenir uniquement des chiffres et caractères spéciaux.',
            'emergency_contact.max' => 'Le numéro d\'urgence ne doit pas dépasser 20 caractères.',
            'avatar.image' => 'Le fichier doit être une image valide (JPG, PNG, GIF).',
            'avatar.max' => 'La photo ne doit pas dépasser 10MB.',
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