<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRoleController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        return view('admin.roles.gestionroles', [
            'admin' => $admin,
        ]);
    }

    public function usersByRole(Request $request, string $rol)
    {
        $admin = Auth::user();

        $allowedRoles = [
            'admin',
            'empleado',
            'dueno',
            'padrino',
        ];

        if (!in_array($rol, $allowedRoles, true)) {
            abort(404);
        }

        $q = trim((string) $request->query('q', ''));

        $usersQuery = User::query()->where('rol', $rol);

        if ($q !== '') {
            $usersQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('id', $q);
            });
        }

        $users = $usersQuery
            ->orderBy('name')
            ->get();

        $rolLabel = match ($rol) {
            'admin' => 'ADMINISTRADOR',
            'empleado' => 'CUIDADOR',
            'dueno' => 'DUEÑO',
            'padrino' => 'PADRINO',
            default => strtoupper($rol),
        };

        return view('admin.roles.usersByRole', [
            'admin' => $admin,
            'rol' => $rol,
            'rolLabel' => $rolLabel,
            'q' => $q,
            'users' => $users,
        ]);
    }
}
