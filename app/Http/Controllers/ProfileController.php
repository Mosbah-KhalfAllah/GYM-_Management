<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    // Afficher le formulaire de modification du profil
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Mettre à jour le profil
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZÀ-ÿ\s\'\-]+$/'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZÀ-ÿ\s\'\-]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:' . now()->subYears(10)->toDateString(), 'after:1920-01-01'],
            'address' => ['nullable', 'string', 'max:1000'],
        ], [
            'first_name.regex' => 'Le prénom ne doit contenir que des lettres.',
            'last_name.regex' => 'Le nom ne doit contenir que des lettres.',
            'phone.regex' => 'Le téléphone doit être au format valide.',
            'birth_date.before_or_equal' => 'L\'age minimum requis est 10 ans.',
            'birth_date.after' => 'La date de naissance doit etre apres 1920.',
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }

    // Afficher le formulaire de changement de mot de passe
    public function password()
    {
        return view('profile.password');
    }

    // Mettre à jour le mot de passe
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Mot de passe mis à jour avec succès.');
    }

    // Supprimer le compte
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Votre compte a été supprimé.');
    }
}