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

        // Marcar todas como leídas inmediatamente al abrir la sección (Opción A) en ambas tablas
        if (Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')
                ->where('user_id', $user->id)
                ->whereNull('leida_en')
                ->update([
                    'leida_en' => now(),
                ]);
        }
        if (Schema::hasTable('notifications')) {
            DB::table('notifications')
                ->where('id_usuario', $user->id)
                ->where('leido', false)
                ->update([
                    'leido' => true,
                    'leido_en' => now(),
                ]);
        }

        $notifications = collect();

        if (Schema::hasTable('notificaciones')) {
            $spanish = DB::table('notificaciones')
                ->where('user_id', $user->id)
                ->get()
                ->map(function ($n) {
                    return (object) [
                        'id' => $n->id,
                        'source' => 'notificaciones',
                        'tipo' => $n->tipo,
                        'titulo' => $n->titulo ?? ucfirst(str_replace('_', ' ', $n->tipo ?? 'notificacion')),
                        'mensaje' => $n->mensaje,
                        'url' => $n->url,
                        'leido' => !empty($n->leida_en),
                        'leida_en' => $n->leida_en,
                        'created_at' => $n->created_at,
                    ];
                });
            $notifications = $notifications->concat($spanish);
        }

        if (Schema::hasTable('notifications')) {
            $english = DB::table('notifications')
                ->where('id_usuario', $user->id)
                ->get()
                ->map(function ($n) {
                    return (object) [
                        'id' => $n->id,
                        'source' => 'notifications',
                        'tipo' => $n->tipo,
                        'titulo' => $n->titulo ?? ucfirst(str_replace('_', ' ', $n->tipo ?? 'notificacion')),
                        'mensaje' => $n->mensaje,
                        'url' => $n->url,
                        'leido' => (bool) ($n->leido || !empty($n->leido_en)),
                        'leida_en' => $n->leido_en ?? ($n->leido ? now() : null),
                        'created_at' => $n->created_at,
                    ];
                });
            $notifications = $notifications->concat($english);
        }

        $notifications = $notifications->sortByDesc('created_at')->values();

        return view('dueños.notificaciones', [
            'user' => $user,
            'notifications' => $notifications,
            'unreadCount' => 0, // Todas acaban de ser marcadas como leídas
        ]);
    }

    public function markAsRead($id)
    {
        $userId = Auth::id();

        if (Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->update([
                    'leida_en' => now(),
                ]);
        }

        if (Schema::hasTable('notifications')) {
            DB::table('notifications')
                ->where('id', $id)
                ->where('id_usuario', $userId)
                ->update([
                    'leido' => true,
                    'leido_en' => now(),
                ]);
        }

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notificación marcada como leída.');
    }

    public function markAllAsRead()
    {
        $userId = Auth::id();

        if (Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')
                ->where('user_id', $userId)
                ->whereNull('leida_en')
                ->update([
                    'leida_en' => now(),
                ]);
        }

        if (Schema::hasTable('notifications')) {
            DB::table('notifications')
                ->where('id_usuario', $userId)
                ->where('leido', false)
                ->update([
                    'leido' => true,
                    'leido_en' => now(),
                ]);
        }

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function getUnreadCount()
    {
        $count = 0;
        $userId = Auth::id();

        if (Schema::hasTable('notificaciones')) {
            $count += DB::table('notificaciones')
                ->where('user_id', $userId)
                ->whereNull('leida_en')
                ->count();
        }

        if (Schema::hasTable('notifications')) {
            $count += DB::table('notifications')
                ->where('id_usuario', $userId)
                ->where('leido', false)
                ->count();
        }

        return response()->json(['count' => $count]);
    }
}
