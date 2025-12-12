<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    /**
     * Display a listing of the members.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'member')
            ->with('membership');
        
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filtre par statut d'adhésion
        if ($request->filled('status')) {
            $query->whereHas('membership', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Filtre par genre
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Tri
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $members = $query->paginate(15);
        
        return view('admin.members.index', compact('members'));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => ['nullable', 'date', 'before_or_equal:' . now()->subYears(10)->toDateString(), 'after:1920-01-01'],
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'emergency_contact' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
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
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'birth_date.date' => 'La date de naissance doit être une date valide.',
            'birth_date.before_or_equal' => 'L\'âge minimum requis est 10 ans.',
            'birth_date.after' => 'La date de naissance doit être après 1920.',
            'address.max' => 'L\'adresse ne doit pas dépasser 500 caractères.',
            'emergency_contact.regex' => 'Le numéro d\'urgence doit contenir uniquement des chiffres et caractères spéciaux.',
            'emergency_contact.max' => 'Le numéro d\'urgence ne doit pas dépasser 20 caractères.',
        ]);

        // Create user
        $user = User::create([
            'role' => 'member',
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'emergency_contact' => $validated['emergency_contact'],
            'is_active' => true,
        ]);

        // Create default membership
        Membership::create([
            'user_id' => $user->id,
            'type' => 'Mensuel',
            'price' => 30,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'status' => 'active',
            'auto_renewal' => true,
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre créé avec succès.');
    }

    /**
     * Display the specified member.
     */
    public function show(User $member)
    {
        // Ensure the user is a member
        if ($member->role !== 'member') {
            abort(404);
        }

        $member->load(['membership', 'attendances', 'programs', 'bookings']);
        
        return view('admin.members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }

        return view('admin.members.edit', compact('member'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s\'-]+$/'],
            'email' => 'required|string|email|max:255|unique:users,email,' . $member->id,
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:' . now()->subYears(10)->toDateString(), 'after:1920-01-01'],
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'emergency_contact' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'is_active' => 'boolean',
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
        ]);

        $member->update($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre mis à jour avec succès.');
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }

        // Soft delete or full delete based on your needs
        $member->update(['is_active' => false]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre désactivé avec succès.');
    }

    /**
     * Redirect to attendance scanner.
     */
    public function generateQrCode(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }

        return redirect()->route('admin.attendance.index')->with('info', 'Consultez la page des présences pour gérer les entrées/sorties.');
    }
}