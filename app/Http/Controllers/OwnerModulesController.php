<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class OwnerModulesController extends Controller
{
    public function reservas()
    {
        $user = Auth::user();

        $reservas = collect();
        if (
            Schema::hasTable('reservas')
            && Schema::hasTable('mascotas')
            && Schema::hasColumn('mascotas', 'id_dueno')
        ) {
            $reservas = DB::table('reservas as r')
                ->join('mascotas as m', 'm.id', '=', 'r.mascota_id')
                ->leftJoin('servicios as s', 's.id', '=', 'r.servicio_id')
                ->leftJoin('users as u', 'u.id', '=', 'r.profesional_id')
                ->where('m.id_dueno', (int) $user->id)
                ->select([
                    'r.id',
                    'r.mascota_id',
                    'r.servicio_id',
                    'r.profesional_id',
                    'r.fecha',
                    'r.hora',
                    'r.estado',
                    'r.comentarios',
                    'r.precio_estimado',
                    'r.created_at',
                    'm.nombre as mascota_nombre',
                    's.nombre as servicio_nombre',
                    'u.name as profesional_nombre',
                ])
                ->orderByDesc('r.created_at')
                ->get();
        }

        $counts = [
            'activas' => 0,
            'confirmadas' => 0,
            'pendientes' => 0,
            'completadas' => 0,
            'historial' => 0,
        ];

        foreach ($reservas as $r) {
            $estado = (string) ($r->estado ?? '');
            if ($estado === 'pendiente') {
                $counts['pendientes']++;
                $counts['activas']++;
            } elseif ($estado === 'confirmado') {
                $counts['confirmadas']++;
                $counts['activas']++;
            } elseif ($estado === 'finalizado') {
                $counts['completadas']++;
                $counts['historial']++;
            } elseif ($estado === 'cancelado') {
                $counts['historial']++;
            }
        }

        return view('dueños.reservas', [
            'user' => $user,
            'reservas' => $reservas,
            'counts' => $counts,
        ]);
    }

    public function seguimiento()
    {
        $user = Auth::user();

        return view('dueños.seguimiento', [
            'user' => $user,
        ]);
    }

    public function pagos()
    {
        $user = Auth::user();

        return view('dueños.pagos', [
            'user' => $user,
        ]);
    }

    public function planPadrino()
    {
        $user = Auth::user();

        return view('dueños.planpadrino', [
            'user' => $user,
        ]);
    }

    public function perfil()
    {
        $user = Auth::user();

        $petCount = 0;
        if (Schema::hasTable('mascotas')) {
            $petCount = (int) DB::table('mascotas')->where('id_dueno', (int) $user->id)->count();
        }

        $dueno = null;
        if (Schema::hasTable('duenos')) {
            $q = DB::table('duenos')->where('id_dueno', (int) $user->id);
            $dueno = $q->first();
        }

        return view('dueños.perfil', [
            'user' => $user,
            'petCount' => $petCount,
            'dueno' => $dueno,
        ]);
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:60'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'documento' => ['nullable', 'string', 'max:80'],
            'ciudad' => ['nullable', 'string', 'max:120'],
            'fecha_nacimiento' => ['nullable', 'date'],
        ]);

        $fullName = trim($validated['nombre'] . ' ' . $validated['apellido']);

        $user->name = $fullName;
        $user->email = (string) $validated['email'];
        $user->save();

        if (Schema::hasTable('duenos')) {
            $cols = Schema::getColumnListing('duenos');

            $payload = [];
            if (in_array('nombre', $cols, true)) {
                $payload['nombre'] = $fullName;
            }
            if (in_array('telefono', $cols, true)) {
                $payload['telefono'] = $validated['telefono'] ?? null;
            }
            if (in_array('direccion', $cols, true)) {
                $payload['direccion'] = $validated['direccion'] ?? null;
            }
            if (in_array('documento', $cols, true)) {
                $payload['documento'] = $validated['documento'] ?? null;
            }
            if (in_array('ciudad', $cols, true)) {
                $payload['ciudad'] = $validated['ciudad'] ?? null;
            }
            if (in_array('fecha_nacimiento', $cols, true)) {
                $payload['fecha_nacimiento'] = $validated['fecha_nacimiento'] ?? null;
            }

            if (!empty($payload)) {
                $exists = DB::table('duenos')->where('id_dueno', (int) $user->id)->exists();

                if ($exists) {
                    DB::table('duenos')->where('id_dueno', (int) $user->id)->update($payload);
                } else {
                    $payload['id_dueno'] = (int) $user->id;

                    if (in_array('created_at', $cols, true)) {
                        $payload['created_at'] = now();
                    }
                    if (in_array('updated_at', $cols, true)) {
                        $payload['updated_at'] = now();
                    }

                    DB::table('duenos')->insert($payload);
                }
            }
        }

        return redirect()
            ->route('owner.perfil')
            ->with('success', 'Perfil actualizado correctamente');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], (string) $user->password)) {
            return redirect()
                ->route('owner.perfil')
                ->withErrors(['current_password' => 'La contraseña actual no es correcta']);
        }

        $user->password = Hash::make((string) $validated['password']);
        $user->save();

        return redirect()
            ->route('owner.perfil')
            ->with('success', 'Contraseña actualizada correctamente');
    }

    public function chat()
    {
        $user = Auth::user();

        return view('dueños.chat', [
            'user' => $user,
        ]);
    }

    public function notificaciones()
    {
        $user = Auth::user();

        return view('dueños.notificaciones', [
            'user' => $user,
        ]);
    }

    public function galeria()
    {
        $user = Auth::user();

        return view('dueños.galeria', [
            'user' => $user,
        ]);
    }
}
