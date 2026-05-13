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
                ->join('mascotas as m', 'm.id', '=', 'r.id_mascota')
                ->leftJoin('servicios as s', 's.id', '=', 'r.id_actividad')
                ->leftJoin('users as u', 'u.id', '=', 'r.id_empleado')
                ->where('m.id_dueno', (int) $user->id)
                ->select([
                    'r.id',
                    'r.id_mascota as mascota_id',
                    'r.id_actividad as servicio_id',
                    'r.id_empleado as profesional_id',
                    'r.fecha',
                    'r.estado',
                    'm.nombre as mascota_nombre',
                    's.nombre as servicio_nombre',
                    'u.name as profesional_nombre',
                ])
                ->orderByDesc('r.id')
                ->get();
        }

        // Separar reservas por estado
        $pendientes = collect();
        $confirmadas = collect();
        $completadas = collect();
        $canceladas = collect();

        foreach ($reservas as $r) {
            $estado = mb_strtolower((string) ($r->estado ?? ''));
            if ($estado === 'pendiente') {
                $pendientes->push($r);
            } elseif ($estado === 'confirmada') {
                $confirmadas->push($r);
            } elseif ($estado === 'finalizada') {
                $completadas->push($r);
            } elseif ($estado === 'cancelada') {
                $canceladas->push($r);
            }
        }

        $counts = [
            'activas' => $pendientes->count() + $confirmadas->count(),
            'confirmadas' => $confirmadas->count(),
            'pendientes' => $pendientes->count(),
            'completadas' => $completadas->count(),
            'historial' => $completadas->count() + $canceladas->count(),
        ];

        return view('dueños.reservas', [
            'user' => $user,
            'reservas' => $reservas,
            'pendientes' => $pendientes,
            'confirmadas' => $confirmadas,
            'completadas' => $completadas,
            'canceladas' => $canceladas,
            'counts' => $counts,
        ])->with('debug', [
            'pendientes_count' => $pendientes->count(),
            'confirmadas_count' => $confirmadas->count(),
            'pendientes_data' => $pendientes->toArray(),
            'confirmadas_data' => $confirmadas->toArray(),
        ]);
    }

    public function seguimiento()
    {
        $user = Auth::user();
        $pets = collect();
        $reports = collect();

        if (Schema::hasTable('mascotas') && Schema::hasColumn('mascotas', 'id_dueno')) {
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';

            $pets = DB::table('mascotas')
                ->where('id_dueno', (int) $user->id)
                ->select([
                    DB::raw("$mascotaKey as id"),
                    DB::raw('COALESCE(nombre, "") as name'),
                    DB::raw('COALESCE(raza, "") as breed'),
                ])
                ->orderBy('name')
                ->get();

            if (Schema::hasTable('seguimientos')) {
                $hasServicios = Schema::hasTable('servicios');
                $hasUsers = Schema::hasTable('users');

                $query = DB::table('seguimientos as sg')
                    ->join('mascotas as m', "m.$mascotaKey", '=', 'sg.id_mascota')
                    ->where('m.id_dueno', (int) $user->id);

                if ($hasServicios) {
                    $query->leftJoin('servicios as s', 's.id', '=', 'sg.id_actividad');
                }
                if ($hasUsers) {
                    $query->leftJoin('users as u', 'u.id', '=', 'sg.id_entrenador');
                }

                $reports = $query
                    ->orderByDesc('sg.created_at')
                    ->select([
                        'sg.id',
                        'sg.estado_animo',
                        'sg.duracion',
                        'sg.nivel_progreso',
                        'sg.notas',
                        'sg.mensaje_dueno',
                        'sg.created_at',
                        DB::raw('COALESCE(m.nombre, "") as pet_name'),
                        DB::raw('COALESCE(m.raza, "") as pet_breed'),
                        DB::raw($hasServicios ? 'COALESCE(s.nombre, "") as activity_name' : '"" as activity_name'),
                        DB::raw($hasUsers ? 'COALESCE(u.name, "") as trainer_name' : '"" as trainer_name'),
                    ])
                    ->get();
            }
        }

        return view('dueños.seguimiento', [
            'user' => $user,
            'pets' => $pets,
            'reports' => $reports,
        ]);
    }

    public function pagos()
    {
        $user = Auth::user();
        $facturas = collect();

        if (
            Schema::hasTable('reservas')
            && Schema::hasTable('mascotas')
            && Schema::hasTable('servicios')
            && Schema::hasColumn('mascotas', 'id_dueno')
        ) {
            $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';

            $facturas = DB::table('reservas as r')
                ->join('mascotas as m', 'm.id', '=', 'r.id_mascota')
                ->leftJoin('servicios as s', 's.id', '=', 'r.id_actividad')
                ->where('m.id_dueno', (int) $user->id)
                ->whereIn('r.estado', ['Confirmada', 'Finalizada'])
                ->select([
                    "r.$reservaKey as id",
                    'r.fecha',
                    'r.estado',
                    'm.nombre as mascota_nombre',
                    's.nombre as servicio_nombre',
                    's.precio as precio',
                ])
                ->orderByDesc("r.$reservaKey")
                ->get()
                ->map(function ($r) {
                    $estado = (string) ($r->estado ?? '');
                    $precio = (float) ($r->precio ?? 0);
                    $pagada = mb_strtolower($estado) === 'finalizada';

                    return (object) [
                        'id' => $r->id,
                        'codigo' => 'RES-' . str_pad((string) $r->id, 5, '0', STR_PAD_LEFT),
                        'descripcion' => trim(($r->servicio_nombre ?? 'Servicio') . ' - ' . ($r->mascota_nombre ?? 'Mascota')),
                        'fecha' => $r->fecha,
                        'estado' => $pagada ? 'Pagada' : 'Pendiente',
                        'precio' => $precio,
                        'pagada' => $pagada,
                    ];
                });
        }

        $pendientes = $facturas->where('pagada', false);
        $pagadas = $facturas->where('pagada', true);
        $ultimoPago = $pagadas->sortByDesc('id')->first();

        return view('dueños.pagos', [
            'user' => $user,
            'facturas' => $facturas,
            'pendienteTotal' => $pendientes->sum('precio'),
            'pendienteCount' => $pendientes->count(),
            'pagadoTotal' => $pagadas->sum('precio'),
            'pagadoCount' => $pagadas->count(),
            'ultimoPago' => $ultimoPago,
        ]);
    }

    public function pagarReserva(Request $request, $reserva)
    {
        $user = Auth::user();

        if (!Schema::hasTable('reservas') || !Schema::hasTable('mascotas') || !Schema::hasColumn('mascotas', 'id_dueno')) {
            return redirect()->back()->withErrors([
                'error' => 'No se pudo validar el pago. Verifica la estructura de la base de datos.',
            ]);
        }

        $row = DB::table('reservas as r')
            ->join('mascotas as m', 'm.id', '=', 'r.id_mascota')
            ->where('r.' . (Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id'), (int) $reserva)
            ->where('m.id_dueno', (int) $user->id)
            ->select([
                'r.' . (Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id') . ' as id',
                'r.estado',
            ])
            ->first();

        if (!$row) {
            return redirect()->route('owner.pagos')->withErrors([
                'error' => 'La reserva no existe o no te pertenece.',
            ]);
        }

        if (mb_strtolower((string) $row->estado) === 'finalizada') {
            return redirect()->route('owner.pagos')->with('success', 'Esta reserva ya fue pagada.');
        }

        if (mb_strtolower((string) $row->estado) === 'pendiente') {
            return redirect()->route('owner.pagos')->withErrors([
                'error' => 'Esta reserva aún está pendiente de confirmación por el entrenador.',
            ]);
        }

        $payload = ['estado' => 'Finalizada'];
        if (Schema::hasColumn('reservas', 'updated_at')) {
            $payload['updated_at'] = now();
        }

        DB::table('reservas')
            ->where(Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id', (int) $row->id)
            ->update($payload);

        return redirect()->route('owner.pagos')->with('success', 'Pago registrado correctamente.');
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

        // Cargar mensajes desde la base de datos
        $dbMessages = \App\Models\ChatMessage::orderBy('created_at', 'asc')->get();
        $messages = $dbMessages->map(function ($msg) use ($user) {
            return [
                'from' => $msg->sender_id === $user->id ? 'me' : 'them',
                'text' => $msg->message,
                'time' => $msg->created_at->format('h:i a'),
            ];
        })->toArray();

        return view('dueños.chat', [
            'user' => $user,
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        // Guardar el mensaje en la base de datos
        \App\Models\ChatMessage::create([
            'sender_id' => Auth::id(),
            'message' => $validated['message'],
            'sender_type' => 'user',
            'is_read' => false,
        ]);

        return response()->json(['success' => true]);
    }

    public function notificaciones()
    {
        $user = Auth::user();
        $notifications = collect();

        if (Schema::hasTable('notificaciones')) {
            $notifications = DB::table('notificaciones')
                ->where('user_id', (int) $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('dueños.notificaciones', [
            'user' => $user,
            'notifications' => $notifications,
            'unreadCount' => $notifications->whereNull('leida_en')->count(),
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
