<?php
// app/Services/QrCodeService.php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    public function generateForUser($user)
    {
        // Generate QR code data
        $data = json_encode([
            'user_id' => $user->id,
            'type' => 'member_access',
            'name' => $user->full_name,
            'email' => $user->email,
            'timestamp' => now()->timestamp
        ]);

        // Generate QR code
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($data);

        // Save to storage
        $path = 'qrcodes/' . $user->id . '_' . now()->timestamp . '.png';
        Storage::disk('public')->put($path, $qrCode);

        return $path;
    }

    public function generateForBooking($booking)
    {
        $data = json_encode([
            'booking_id' => $booking->id,
            'class_id' => $booking->class_id,
            'member_id' => $booking->member_id,
            'booking_code' => $booking->booking_code,
            'timestamp' => now()->timestamp
        ]);

        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($data);

        $path = 'bookings/qrcodes/' . $booking->booking_code . '.png';
        Storage::disk('public')->put($path, $qrCode);

        return $path;
    }

    public function validateQrCode($qrCodeData)
    {
        $data = json_decode($qrCodeData, true);

        if (!isset($data['type'])) {
            return false;
        }

        switch ($data['type']) {
            case 'member_access':
                return $this->validateMemberAccess($data);
            case 'booking':
                return $this->validateBooking($data);
            default:
                return false;
        }
    }

    private function validateMemberAccess($data)
    {
        if (!isset($data['user_id']) || !isset($data['timestamp'])) {
            return false;
        }

        // Check if QR code is expired (older than 1 day)
        $timestamp = $data['timestamp'];
        if (now()->timestamp - $timestamp > 86400) {
            return false;
        }

        // Check if user exists and is active
        $user = \App\Models\User::find($data['user_id']);
        
        if (!$user || !$user->is_active) {
            return false;
        }

        // Check if membership is active
        if (!$user->membership || $user->membership->status !== 'active') {
            return false;
        }

        return $user;
    }

    private function validateBooking($data)
    {
        if (!isset($data['booking_id']) || !isset($data['booking_code'])) {
            return false;
        }

        $booking = \App\Models\ClassBooking::where('id', $data['booking_id'])
            ->where('booking_code', $data['booking_code'])
            ->first();

        if (!$booking || $booking->status !== 'confirmed') {
            return false;
        }

        return $booking;
    }
}