<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class NotificationController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $nonLues = Notification::where('user_id', $user->id)
            ->where('est_lu', false)
            ->count();

        // Déterminer le layout en fonction du rôle
        $layout = 'layouts.menage';
        if ($user->role ==='admin') {
            $layout = 'layouts.admin';
        } elseif ($user->role === 'collecteur') {
            $layout = 'layouts.collecteur';
        } elseif ($user->role ==='partenaire') {
            $layout = 'layouts.partenaire';
        }

        return view('notifications.index', compact('notifications', 'nonLues', 'layout'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        if ($notification->lien) {
            return redirect($notification->lien);
        }

        return redirect()->route('notifications.index')->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('est_lu', false)
            ->update(['est_lu' => true]);

        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function destroy($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return redirect()->back()->with('success', 'Notification supprimée.');
    }
}
