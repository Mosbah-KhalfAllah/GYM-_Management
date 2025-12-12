<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Marquer toutes les notifications non lues comme lues
        $user->unreadNotifications->markAsRead();

        $notifications = $user->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }
}