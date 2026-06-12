<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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
            $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';

            $reservas = DB::table('reservas as r')
                ->join('mascotas as m', "m.$mascotaKey", '=', 'r.id_mascota')
                ->leftJoin('servicios as s', 's.id', '=', 'r.id_actividad')
                ->leftJoin('users as u', 'u.id', '=', 'r.id_empleado')
                ->where('m.id_dueno', (int) $user->id)
                ->select([
                    "r.$reservaKey as id",
                    'r.id_mascota as mascota_id',
                    'r.id_actividad as servicio_id',
                    'r.id_empleado as profesional_id',
                    'r.fecha',
                    'r.estado',
                    'r.fecha_evaluacion',
                    'r.hora_evaluacion',
                    'r.precio',
                    'r.duracion',
                    'r.observaciones',
                    'm.nombre as mascota_nombre',
                    's.nombre as servicio_nombre',
                    'u.name as profesional_nombre',
                ])
                ->orderByDesc("r.$reservaKey")
                ->get();
        }

        // Separar reservas por estado
        $pendientes = collect();
        $confirmadas = collect();
        $completadas = collect();
        $canceladas = collect();

        foreach ($reservas as $r) {
            $estado = mb_strtolower((string) ($r->estado ?? ''));
            
            // Usar created_at si existe, si no usar fecha para determinar antigüedad
            $dateValue = $r->created_at ?? $r->fecha ?? null;
            $isOld = $dateValue && \Carbon\Carbon::parse($dateValue)->lt(now()->subDays(7));

            if ($estado === 'pendiente' || $estado === 'pendiente de evaluación' || $estado === 'cita de evaluación asignada') {
                $pendientes->push($r);
            } elseif ($estado === 'confirmada' || $estado === 'cotizado / pendiente de aprobación' || $estado === 'pagado / en curso' || $estado === 'aceptada / esperando pago') {
                $confirmadas->push($r);
            } elseif ($estado === 'finalizada') {
                $completadas->push($r);
            } elseif ($estado === 'cancelada' || $estado === 'rechazada por el cliente') {
                // Solo mostrar en historial si no es muy antigua
                if (!$isOld) {
                    $canceladas->push($r);
                }
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
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';

            $facturas = DB::table('reservas as r')
                ->join('mascotas as m', "m.$mascotaKey", '=', 'r.id_mascota')
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

        // Filtrar facturas para la vista: mantener todas las pendientes y solo las pagadas de los últimos 3 días
        $facturasMostradas = $facturas->filter(function($f) {
            if (!$f->pagada) return true;
            // Si la reserva no tiene fecha, la mantenemos por seguridad
            if (!$f->fecha) return true;
            try {
                return \Carbon\Carbon::parse($f->fecha)->gte(now()->subDays(3));
            } catch (\Exception $e) {
                return true;
            }
        });

        return view('dueños.pagos', [
            'user' => $user,
            'facturas' => $facturasMostradas,
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

        $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';
        $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';

        $row = DB::table('reservas as r')
            ->join('mascotas as m', "m.$mascotaKey", '=', 'r.id_mascota')
            ->where("r.$reservaKey", (int) $reserva)
            ->where('m.id_dueno', (int) $user->id)
            ->select([
                "r.$reservaKey as id",
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
            ->where($reservaKey, (int) $row->id)
            ->update($payload);

        return redirect()->route('owner.pagos')->with('success', 'Pago registrado correctamente.');
    }

    public function planPadrino()
    {
        $user = Auth::user();
        $dogs = \App\Models\SponsorDog::query()
            ->where('publicado', true)
            ->where('estado', 'Disponible')
            ->orderByDesc('id')
            ->get();

        $sponsorships = \App\Models\Sponsorship::query()
            ->where('user_id', (int) $user->id)
            ->where('estado', '!=', 'Pendiente')
            ->orderByDesc('id')
            ->get()
            ->keyBy('sponsor_dog_id');

        return view('dueños.planpadrino', [
            'user' => $user,
            'dogs' => $dogs,
            'sponsorships' => $sponsorships,
        ]);
    }

    public function storePadrinazgo(Request $request, \App\Models\SponsorDog $dog)
    {
        $validated = $request->validate([
            'plan' => ['required', 'string', 'in:basico,cuidador,protector'],
        ]);

        $amount = match ($validated['plan']) {
            'cuidador' => 50000,
            'protector' => 100000,
            default => 30000,
        };

        \App\Models\Sponsorship::query()->updateOrCreate([
            'user_id' => (int) Auth::id(),
            'sponsor_dog_id' => (int) $dog->id,
        ], [
            'plan' => $validated['plan'],
            'monto_mensual' => $amount,
            'estado' => 'Pendiente',
        ]);

        return redirect()
            ->route('owner.planpadrino')
            ->with('success', 'Tu solicitud de apadrinamiento fue registrada. Te contactaremos para coordinar el aporte.');
    }

    public function perfil()
    {
        $user = Auth::user();

        $petCount = 0;
        if (Schema::hasTable('mascotas')) {
            $petCount = (int) DB::table('mascotas')->where('id_dueno', (int) $user->id)->count();
        }

        $dueno = \App\Models\Dueno::where('id_dueno', (int) $user->id)->first();

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
            'nombre' => ['nullable', 'string', 'max:255'],
            'apellido' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:60'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'documento' => ['nullable', 'string', 'max:80'],
            'ciudad' => ['nullable', 'string', 'max:120'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Manejar subida de Avatar si existe
        if ($request->hasFile('avatar')) {
            // Eliminar viejo si existe
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->save();

            // Si solo se envió el avatar (submit automático), retornar aquí
            if (!$request->has('nombre')) {
                return redirect()->route('owner.perfil')->with('success', 'Foto de perfil actualizada correctamente');
            }
        }

        if ($request->has('nombre')) {
            $fullName = trim($validated['nombre'] . ' ' . $validated['apellido']);

            // 1. Actualizar datos del usuario principal
            $user->name = $fullName;
            $user->email = (string) ($validated['email'] ?? $user->email);
            $user->save();

            // 2. Actualizar o crear datos extendidos en la tabla 'duenos'
            \App\Models\Dueno::updateOrCreate(
                ['id_dueno' => (int) $user->id],
                [
                    'nombre' => $fullName,
                    'telefono' => $validated['telefono'] ?? null,
                    'direccion' => $validated['direccion'] ?? null,
                    'documento' => $validated['documento'] ?? null,
                    'ciudad' => $validated['ciudad'] ?? null,
                    'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                ]
            );
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

        $trainer = Schema::hasTable('users')
            ? DB::table('users')->where('rol', 'entrenador')->orderBy('id')->first()
            : null;

        // Guardar el mensaje en la base de datos
        \App\Models\ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $trainer->id ?? null,
            'message' => $validated['message'],
            'sender_type' => 'user',
            'is_read' => false,
        ]);

        if ($trainer && Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')->updateOrInsert([
                'user_id' => (int) $trainer->id,
                'tipo' => 'chat',
                'url' => route('entrenador.chat', [], false),
            ], [
                'user_id' => (int) $trainer->id,
                'tipo' => 'chat',
                'titulo' => 'Nuevo mensaje de chat',
                'mensaje' => Auth::user()->name . ' te envió un mensaje.',
                'url' => route('entrenador.chat', [], false),
                'leida_en' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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
        $directory = 'gallery/' . (int) $user->id;
        $photos = collect(Storage::disk('public')->files($directory))
            ->filter(fn ($file) => preg_match('/\.(jpe?g|png|webp|gif)$/i', $file))
            ->sortDesc()
            ->map(fn ($file) => [
                'path' => $file,
                'url' => Storage::url($file),
                'name' => basename($file),
            ])
            ->values();

        return view('dueños.galeria', [
            'user' => $user,
            'photos' => $photos,
        ]);
    }

    public function uploadGaleria(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        foreach ($validated['photos'] as $photo) {
            $photo->store('gallery/' . (int) $user->id, 'public');
        }

        return redirect()
            ->route('owner.galeria')
            ->with('success', 'Fotos subidas correctamente.');
    }

    public function destroyPhoto($photo)
    {
        $user = Auth::user();
        $path = 'gallery/' . (int) $user->id . '/' . $photo;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return redirect()->route('owner.galeria')->with('success', 'Foto eliminada.');
        }

        return redirect()->route('owner.galeria')->with('error', 'La foto no existe o no se pudo eliminar.');
    }
}
