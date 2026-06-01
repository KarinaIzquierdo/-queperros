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

        if (Schema::hasTable('notificaciones')) {
            $notifications = DB::table('notificaciones')
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('dueños.notificaciones', [
            'user' => $user,
            'notifications' => $notifications,
            'unreadCount' => $notifications->whereNull('leida_en')->count(),
        ]);
    }

    public function markAsRead($id)
    {
        if (Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->update([
                    'leida_en' => now(),
                ]);
        }

        return redirect()->back()->with('success', 'Notificación marcada como leída.');
    }

    public function markAllAsRead()
    {
        if (Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')
                ->where('user_id', Auth::id())
                ->whereNull('leida_en')
                ->update([
                    'leida_en' => now(),
                ]);
        }

        return redirect()->back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function getUnreadCount()
    {
        $count = 0;
        if (Schema::hasTable('notificaciones')) {
            $count = DB::table('notificaciones')
                ->where('user_id', Auth::id())
                ->whereNull('leida_en')
                ->count();
        }

        return response()->json(['count' => $count]);
    }
}
