<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $users = User::query()->orderByDesc('id')->get();
        $recentUsers = User::query()->orderByDesc('id')->limit(4)->get();

        $ownersCount = User::query()->where('rol', 'dueno')->count();
        $vetsCount = User::query()->where('rol', 'empleado')->count();
        $adminsCount = User::query()->where('rol', 'admin')->count();

        $stats = [
            'total_users' => User::query()->count(),
            'active_services' => 6,
            'defined_roles' => 3,
            'owners_count' => $ownersCount,
            'vets_count' => $vetsCount,
            'admins_count' => $adminsCount,
        ];

        return view('admin.dashboardAdmin', [
            'user' => $user,
            'stats' => $stats,
            'users' => $users,
            'recentUsers' => $recentUsers,
        ]);
    }
}
