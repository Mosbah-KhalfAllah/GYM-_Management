<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{
    /**
     * Display the member's QR code.
     */
    public function show()
    {
        $user = Auth::user();
        
        // Générer ou récupérer le QR Code
        $qrCode = $this->generateOrGetQrCode($user);
        
        // Informations à encoder dans le QR Code
        $qrData = json_encode([
            'user_id' => $user->id,
            'type' => 'member_access',
            'name' => $user->full_name,
            'email' => $user->email,
            'timestamp' => now()->timestamp,
            'expires_at' => now()->addDay()->timestamp,
        ]);
        
        // Générer le QR Code en base64 pour l'affichage (SVG to avoid Imagick requirement)
        $qrSvg = QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->generate($qrData);
        $qrCodeBase64 = base64_encode($qrSvg);
        
        // Statistiques d'utilisation
        $qrStats = [
            'today_scans' => \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('check_in', today())
                ->where('entry_method', 'qr_code')
                ->count(),
            'total_scans' => \App\Models\Attendance::where('user_id', $user->id)
                ->where('entry_method', 'qr_code')
                ->count(),
            'last_scan' => \App\Models\Attendance::where('user_id', $user->id)
                ->where('entry_method', 'qr_code')
                ->latest()
                ->first(),
        ];
        
        return view('member.qrcode.show', compact(
            'user',
            'qrCodeBase64',
            'qrStats',
            'qrData'
        ));
    }

    /**
     * Generate or get QR code for user.
     */
    private function generateOrGetQrCode($user)
    {
        // Si l'utilisateur a déjà un QR Code, le retourner
        if ($user->qr_code_path && Storage::disk('public')->exists($user->qr_code_path)) {
            return $user->qr_code_path;
        }
        
        // Générer les données du QR Code
        $qrData = json_encode([
            'user_id' => $user->id,
            'type' => 'member_access',
            'name' => $user->full_name,
            'email' => $user->email,
            'timestamp' => now()->timestamp,
        ]);
        
        // Générer le QR Code en SVG (safer when Imagick not installed)
        $qrSvg = QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->generate($qrData);

        // Sauvegarder le QR Code (SVG)
        $path = 'qrcodes/' . $user->id . '_' . now()->timestamp . '.svg';
        Storage::disk('public')->put($path, $qrSvg);
        
        // Mettre à jour l'utilisateur
        $user->update(['qr_code_path' => $path]);
        
        return $path;
    }

    /**
     * Download the QR code.
     */
    public function download()
    {
        $user = Auth::user();
        
        // Générer ou récupérer le QR Code
        $path = $this->generateOrGetQrCode($user);
        
        $absolutePath = Storage::disk('public')->path($path);

        // Determine content type and filename from extension
        $ext = pathinfo($absolutePath, PATHINFO_EXTENSION);
        $mime = $ext === 'svg' ? 'image/svg+xml' : ($ext === 'png' ? 'image/png' : 'application/octet-stream');
        $filename = 'qrcode-' . $user->id . '.' . $ext;

        return response()->download($absolutePath, $filename, [
            'Content-Type' => $mime,
        ]);
    }

    /**
     * Generate new QR code.
     */
    public function regenerate()
    {
        $user = Auth::user();
        
        // Supprimer l'ancien QR Code s'il existe
        if ($user->qr_code_path && Storage::disk('public')->exists($user->qr_code_path)) {
            Storage::disk('public')->delete($user->qr_code_path);
        }
        
        // Générer un nouveau QR Code
        $this->generateOrGetQrCode($user);
        
        return redirect()->route('member.qrcode')
            ->with('success', 'QR Code régénéré avec succès.');
    }

    /**
     * Show QR code usage instructions.
     */
    public function instructions()
    {
        return view('member.qrcode.instructions');
    }
}