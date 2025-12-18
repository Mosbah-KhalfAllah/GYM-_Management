<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\User;
use App\Models\MaintenanceLog;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::with('assignedUser')->latest()->paginate(15);
        $availableCount = Equipment::where('status', 'available')->count();
        $maintenanceCount = Equipment::where('status', 'maintenance')->count();
        $brokenCount = Equipment::where('status', 'broken')->count();
        
        return view('admin.equipment.index', compact('equipment', 'availableCount', 'maintenanceCount', 'brokenCount'));
    }

    public function create()
    {
        $coaches = User::where('role', 'coach')->where('is_active', true)->get();
        return view('admin.equipment.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|min:2',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:available,maintenance,broken',
            'notes' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Le nom de l\'équipement est requis.',
            'name.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 500 caractères.',
        ]);

        Equipment::create($validated);
        return redirect()->route('admin.equipment.index')
            ->with('success', 'Équipement ajouté avec succès.');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load(['assignedUser', 'maintenanceLogs.technician']);
        return view('admin.equipment.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $coaches = User::where('role', 'coach')->where('is_active', true)->get();
        return view('admin.equipment.edit', compact('equipment', 'coaches'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|min:2',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:available,maintenance,broken',
            'notes' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Le nom de l\'équipement est requis.',
            'name.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 500 caractères.',
        ]);

        $equipment->update($validated);
        return redirect()->route('admin.equipment.index')
            ->with('success', 'Équipement mis à jour avec succès.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('admin.equipment.index')
            ->with('success', 'Équipement supprimé avec succès.');
    }

    public function logMaintenance(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'maintenance_date' => 'required|date',
            'description' => 'required|string',
            'type' => 'required|in:preventive,corrective,emergency',
            'cost' => 'nullable|numeric|min:0',
            'parts_replaced' => 'nullable|string',
            'next_maintenance_date' => 'required|date',
            'technician_id' => 'nullable|exists:users,id',
        ]);

        // Créer le log de maintenance
        MaintenanceLog::create(array_merge($validated, [
            'equipment_id' => $equipment->id,
        ]));

        // Mettre à jour les dates de maintenance de l'équipement
        $equipment->update([
            'last_maintenance_date' => $validated['maintenance_date'],
            'next_maintenance_date' => $validated['next_maintenance_date'],
            'status' => 'available', // Remettre à disponible après maintenance
        ]);

        return redirect()->route('admin.equipment.show', $equipment)
            ->with('success', 'Maintenance enregistrée avec succès.');
    }
}