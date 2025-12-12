<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    public function logSecurityEvent(Request $request)
    {
        $validated = $request->validate([
            'event_type' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'severity' => 'required|in:low,medium,high,critical'
        ]);

        Log::channel('security')->info('Événement de sécurité', [
            'user_id' => Auth::id(),
            'event_type' => $validated['event_type'],
            'description' => $validated['description'],
            'severity' => $validated['severity'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        return response()->json(['status' => 'logged']);
    }
}