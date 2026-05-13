<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    private function roleIdFromRol(string $rol): int
    {
        return match ($rol) {
            'admin' => 1,
            'dueno', 'padrino' => 2,
            'empleado', 'entrenador', 'cuidador', 'profesional' => 3,
            default => 2,
        };
    }

    public function index()
    {
        $admin = Auth::user();

        // Más adelante puedes aplicar filtros / paginación
        $users = User::query()->orderByDesc('id')->get();

        $totalUsers = $users->count();
        $activeUsers = $users->whereNotNull('email_verified_at')->count();
        $inactiveUsers = $users->whereNull('email_verified_at')->count();
        $definedRoles = $users->pluck('rol')->filter()->unique()->sort()->values();
        $definedRolesCount = $definedRoles->count();

        return view('admin.users.gestionusarios', [
            'admin' => $admin,
            'users' => $users,
            'rolesList' => $definedRoles,
            'stats' => [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'inactive_users' => $inactiveUsers,
                'defined_roles' => $definedRolesCount,
            ],
        ])->with('debug_roles', $definedRoles->toArray());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'rol' => ['required', 'string', 'in:admin,empleado,dueno,padrino,entrenador,cuidador,profesional'],
        ]);

        $tempPassword = Str::password(12);

        $data = [
            'email' => $validated['email'],
            'password' => Hash::make($tempPassword),
        ];

        if (Schema::hasColumn('users', 'name')) {
            $data['name'] = $validated['name'];
        }

        if (Schema::hasColumn('users', 'nombre')) {
            $data['nombre'] = $validated['name'];
        }

        if (Schema::hasColumn('users', 'rol')) {
            $data['rol'] = $validated['rol'];
        }

        if (Schema::hasColumn('users', 'rol_id')) {
            $data['rol_id'] = $this->roleIdFromRol($validated['rol']);
        }

        User::create($data);

        return redirect()
            ->route('admin.users')
            ->with('status', 'Usuario registrado correctamente')
            ->with('temp_password', $tempPassword);
    }

    public function assignRole(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'rol' => ['required', 'string', 'in:admin,empleado,dueno,padrino,entrenador,cuidador,profesional'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        if (Schema::hasColumn('users', 'rol')) {
            $user->rol = $validated['rol'];
        }

        if (Schema::hasColumn('users', 'rol_id')) {
            $user->rol_id = $this->roleIdFromRol($validated['rol']);
        }

        $user->save();

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Rol asignado correctamente');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()
                ->route('admin.users')
                ->with('error', 'No puedes eliminar tu propio usuario');
        }

        DB::beginTransaction();
        try {
            // Eliminar reservas asociadas a las mascotas del usuario
            if (Schema::hasTable('reservas') && Schema::hasTable('mascotas')) {
                $mascotaIds = DB::table('mascotas')
                    ->where('id_dueno', $user->id)
                    ->pluck('id');

                if ($mascotaIds->isNotEmpty()) {
                    DB::table('reservas')
                        ->whereIn('id_mascota', $mascotaIds)
                        ->delete();
                }
            }

            // Eliminar mascotas del usuario
            if (Schema::hasTable('mascotas')) {
                $mascotas = DB::table('mascotas')
                    ->where('id_dueno', $user->id)
                    ->get();

                foreach ($mascotas as $mascota) {
                    // Eliminar foto si existe
                    if ($mascota->foto && Storage::disk('public')->exists($mascota->foto)) {
                        Storage::disk('public')->delete($mascota->foto);
                    }
                }

                DB::table('mascotas')
                    ->where('id_dueno', $user->id)
                    ->delete();
            }

            // Eliminar el usuario
            $user->delete();

            DB::commit();

            return redirect()
                ->route('admin.users')
                ->with('status', 'Usuario eliminado correctamente junto con todos sus datos');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.users')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}
