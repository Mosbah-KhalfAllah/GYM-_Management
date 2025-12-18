<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();
        
        // Vérifier si l'utilisateur est actif
        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Votre compte a été désactivé. Contactez l\'administrateur.',
            ]);
        }

        $request->session()->regenerate();

        // Rediriger selon le rôle de l'utilisateur
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'coach':
                return redirect()->route('coach.dashboard');
            case 'member':
                return redirect()->route('member.dashboard');
            default:
                return redirect()->route('member.dashboard');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}