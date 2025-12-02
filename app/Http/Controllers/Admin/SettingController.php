<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'gym_name' => config('app.name', 'GYM Pro'),
            'gym_address' => '123 Rue du Sport, 75000 Paris',
            'gym_phone' => '01 23 45 67 89',
            'gym_email' => 'contact@gympro.com',
            'opening_hours' => '06:00 - 23:00',
            'membership_types' => ['Mensuel', 'Annuel', 'Premium'],
            'qr_code_expiry_days' => 30,
            'membership_reminder_days' => 5,
            'maintenance_reminder_days' => 7,
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'gym_name' => 'required|string|max:255',
            'gym_address' => 'required|string|max:500',
            'gym_phone' => 'required|string|max:20',
            'gym_email' => 'required|email|max:255',
            'opening_hours' => 'required|string|max:100',
            'qr_code_expiry_days' => 'required|integer|min:1|max:365',
            'membership_reminder_days' => 'required|integer|min:1|max:30',
            'maintenance_reminder_days' => 'required|integer|min:1|max:90',
        ]);

        // Enregistrer les paramètres (dans un vrai projet, vous utiliseriez une table settings)
        // Pour l'instant, on simule l'enregistrement
        session()->flash('settings_saved', true);

        // Gestion du logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $path = $logo->store('logos', 'public');
            session()->flash('logo_path', $path);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès.')
            ->with('validated', $validated);
    }
}