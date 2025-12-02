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
        $equipment = Equipment::with('assignedUser')->latest()->paginate(10);
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
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'serial_number' => 'required|string|max:100|unique:equipment',
            'purchase_date' => 'required|date',
            'last_maintenance_date' => 'required|date',
            'next_maintenance_date' => 'required|date',
            'status' => 'required|in:available,in_use,maintenance,broken',
            'location' => 'required|string|max:255',
            'purchase_price' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
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
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'serial_number' => 'required|string|max:100|unique:equipment,serial_number,' . $equipment->id,
            'purchase_date' => 'required|date',
            'last_maintenance_date' => 'required|date',
            'next_maintenance_date' => 'required|date',
            'status' => 'required|in:available,in_use,maintenance,broken',
            'location' => 'required|string|max:255',
            'purchase_price' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
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