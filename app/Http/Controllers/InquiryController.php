<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function create()
    {
        return view('inquiry.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50|regex:/^[a-zA-ZÀ-ÿÀ-ÿ\s\'\-]+$/',
            'last_name' => 'required|string|max:50|regex:/^[a-zA-ZÀ-ÿÀ-ÿ\s\'\-]+$/',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20|regex:/^[\d\s\+\-\(\)]+$/',
            'subject' => 'required|in:membership,classes,personal_training,other',
            'message' => 'required|string|max:500',
        ]);

        // Enregistrer en base de données
        Inquiry::create($validated);

        // Rediriger vers la page d'accueil avec message de succès
        return redirect()->route('home')
            ->with('inquiry_success', 'Votre demande a été envoyée avec succès ! Nous vous contacterons sous 24h.');
    }
}