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
    public function index()
    {
        $members = User::where('role', 'member')
            ->with('membership')
            ->latest()
            ->paginate(10);
        
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:20',
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:20',
            'is_active' => 'boolean',
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
     * Generate QR Code for member.
     */
    public function generateQrCode(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }

        // Simuler la génération de QR Code
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . 
                    urlencode(json_encode([
                        'user_id' => $member->id,
                        'name' => $member->full_name,
                        'email' => $member->email,
                    ]));

        return view('admin.members.qr-code', compact('member', 'qrCodeUrl'));
    }
}