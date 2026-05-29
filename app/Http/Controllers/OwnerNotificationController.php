<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OwnerNotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = collect();

        if (Schema::hasTable('notifications')) {
            $notifications = DB::table('notifications')
                ->where('id_usuario', $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('dueños.notificaciones', [
            'user' => $user,
            'notifications' => $notifications,
            'unreadCount' => $notifications->where('leido', false)->count(),
        ]);
    }

    public function markAsRead($id)
    {
        if (Schema::hasTable('notifications')) {
            DB::table('notifications')
                ->where('id', $id)
                ->where('id_usuario', Auth::id())
                ->update([
                    'leido' => true,
                    'leido_en' => now(),
                ]);
        }

        return redirect()->back()->with('success', 'Notificación marcada como leída.');
    }

    public function markAllAsRead()
    {
        if (Schema::hasTable('notifications')) {
            DB::table('notifications')
                ->where('id_usuario', Auth::id())
                ->where('leido', false)
                ->update([
                    'leido' => true,
                    'leido_en' => now(),
                ]);
        }

        return redirect()->back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function getUnreadCount()
    {
        $count = 0;
        if (Schema::hasTable('notifications')) {
            $count = DB::table('notifications')
                ->where('id_usuario', Auth::id())
                ->where('leido', false)
                ->count();
        }

        return response()->json(['count' => $count]);
    }
}
