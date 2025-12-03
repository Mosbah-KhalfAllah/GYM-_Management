<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    /**
     * Display the member's QR code.
     */
    public function show()
    {
        $user = Auth::user();

        // QR flow removed: show a single modern button to toggle attendance
        // Provide minimal stats (manual entries)
        $qrStats = [
            'today_scans' => \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('check_in', today())
                ->where('entry_method', 'manual')
                ->count(),
            'total_scans' => \App\Models\Attendance::where('user_id', $user->id)
                ->where('entry_method', 'manual')
                ->count(),
            'last_scan' => \App\Models\Attendance::where('user_id', $user->id)
                ->where('entry_method', 'manual')
                ->latest()
                ->first(),
        ];

        return view('member.attendance.show', compact(
            'user',
            'qrStats'
        ));
    }

    /**
     * Generate or get QR code for user.
     */
    private function generateOrGetQrCode($user)
    {
        // QR generation removed; keep method for compatibility but return null
        return null;
    }

    /**
     * Download the QR code.
     */
    public function download()
    {
        return redirect()->route('member.attendance')->with('info', 'Fonction QR Code désactivée.');
    }

    /**
     * Generate new QR code.
     */
    public function regenerate()
    {
        return redirect()->route('member.attendance')->with('info', 'Fonction QR Code désactivée.');
    }

    /**
     * Show QR code usage instructions.
     */
    public function instructions()
    {
        return view('member.attendance.instructions');
    }
}