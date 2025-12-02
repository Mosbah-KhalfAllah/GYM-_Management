<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = User::where('role', 'coach')->latest()->paginate(10);
        return view('admin.coaches.index', compact('coaches'));
    }

    public function create()
    {
        return view('admin.coaches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $coach->id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
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