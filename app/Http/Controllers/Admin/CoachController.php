<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CoachController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'coach');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Tri
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $coaches = $query->with('createdPrograms')->paginate(15);
        return view('admin.coaches.index', compact('coaches'));
    }

    public function create()
    {
        return view('admin.coaches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0|max:60',
        ], [
            'first_name.regex' => 'Le prénom ne doit contenir que des lettres.',
            'last_name.regex' => 'Le nom ne doit contenir que des lettres.',
            'phone.regex' => 'Le téléphone doit être au format valide.',
            'experience_years.max' => 'Les années d\'expérience ne peuvent pas dépasser 60.',
        ]);

        $coach = User::create([
            'role' => 'coach',
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        return redirect()->route('admin.coaches.index')
            ->with('success', 'Coach créé avec succès.');
    }

    public function show(User $coach)
    {
        if ($coach->role !== 'coach') abort(404);
        $coach->load(['createdPrograms', 'classesCoached']);
        return view('admin.coaches.show', compact('coach'));
    }

    public function edit(User $coach)
    {
        if ($coach->role !== 'coach') abort(404);
        return view('admin.coaches.edit', compact('coach'));
    }

    public function update(Request $request, User $coach)
    {
        if ($coach->role !== 'coach') abort(404);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'email' => 'required|string|email|max:255|unique:users,email,' . $coach->id,
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'is_active' => 'boolean',
        ], [
            'first_name.regex' => 'Le prénom ne doit contenir que des lettres.',
            'last_name.regex' => 'Le nom ne doit contenir que des lettres.',
            'phone.regex' => 'Le téléphone doit être au format valide.',
        ]);

        $coach->update($validated);
        return redirect()->route('admin.coaches.index')
            ->with('success', 'Coach mis à jour avec succès.');
    }

    public function destroy(User $coach)
    {
        if ($coach->role !== 'coach') abort(404);
        $coach->update(['is_active' => false]);
        return redirect()->route('admin.coaches.index')
            ->with('success', 'Coach désactivé avec succès.');
    }
}