<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TrainerModulesController extends Controller
{
    public function misTareas()
    {
        $user = Auth::user();

        $tasks = [];

        return view('entrenador.mistareas', [
            'user' => $user,
            'tasks' => $tasks,
        ]);
    }

    public function mascotasAsignadas()
    {
        $user = Auth::user();

        $pets = collect();

        if (Schema::hasTable('reservas') && Schema::hasTable('mascotas')) {
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $hasUsers = Schema::hasTable('users') && Schema::hasColumn('mascotas', 'id_dueno');
            $hasServicios = Schema::hasTable('servicios');
            $hasActividades = !$hasServicios && Schema::hasTable('actividades');

            $query = DB::table('reservas as r')
                ->join('mascotas as m', "m.$mascotaKey", '=', 'r.id_mascota')
                ->where('r.id_empleado', (int) $user->id);

            if ($hasServicios) {
                $query->leftJoin('servicios as s', 's.id', '=', 'r.id_actividad');
            } elseif ($hasActividades) {
                $query->leftJoin('actividades as a', 'a.id_actividad', '=', 'r.id_actividad');
            }

            if ($hasUsers) {
                $query->leftJoin('users as u', 'u.id', '=', 'm.id_dueno');
            }

            $pets = $query
                ->select([
                    DB::raw("m.$mascotaKey as pet_id"),
                    DB::raw('COALESCE(m.nombre, "") as name'),
                    DB::raw('COALESCE(m.raza, "") as breed'),
                    DB::raw('COALESCE(m.edad, "") as age'),
                    DB::raw($hasUsers ? 'COALESCE(u.name, "") as owner' : '"" as owner'),
                    DB::raw('COALESCE(m.telefono, "") as phone'),
                ])
                ->distinct()
                ->orderBy('name')
                ->get()
                ->map(fn ($pet) => [
                    'name' => $pet->name,
                    'breed' => $pet->breed,
                    'age' => $pet->age !== '' ? $pet->age . ' años' : '',
                    'owner' => $pet->owner,
                    'phone' => $pet->phone,
                    'tags' => [],
                ]);
        }

        return view('entrenador.mascotas', [
            'user' => $user,
            'pets' => $pets,
        ]);
    }

    public function seguimiento()
    {
        $user = Auth::user();

        $pets = collect();
        $activities = collect();

        if (Schema::hasTable('reservas') && Schema::hasTable('mascotas')) {
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';

            $petsQuery = DB::table('reservas as r')
                ->join('mascotas as m', "m.$mascotaKey", '=', 'r.id_mascota')
                ->where('r.id_empleado', (int) $user->id)
                ->whereIn('r.estado', ['Pendiente', 'Confirmada']);

            if (Schema::hasTable('servicios')) {
                $petsQuery->leftJoin('servicios as s', 's.id', '=', 'r.id_actividad');
            } elseif (Schema::hasTable('actividades')) {
                $petsQuery->leftJoin('actividades as a', 'a.id_actividad', '=', 'r.id_actividad');
            }

            $pets = $petsQuery
                ->select([
                    DB::raw("m.$mascotaKey as id"),
                    DB::raw('COALESCE(m.nombre, "") as name'),
                    DB::raw('COALESCE(m.raza, "") as breed'),
                ])
                ->distinct()
                ->orderBy('name')
                ->get();
        }

        if (Schema::hasTable('servicios')) {
            $activities = DB::table('servicios')
                ->when(Schema::hasColumn('servicios', 'activo'), fn ($query) => $query->where('activo', true))
                ->select([
                    DB::raw('id as id'),
                    DB::raw('COALESCE(nombre, "") as name'),
                ])
                ->orderBy('name')
                ->get();
        } elseif (Schema::hasTable('actividades')) {
            $activities = DB::table('actividades')
                ->select([
                    DB::raw('id_actividad as id'),
                    DB::raw('COALESCE(tipo_actividad, "") as name'),
                ])
                ->orderBy('name')
                ->get();
        }

        return view('entrenador.seguimiento', [
            'user' => $user,
            'pets' => $pets,
            'activities' => $activities,
        ]);
    }

    public function storeSeguimiento(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'pet' => ['required', 'integer'],
            'activity' => ['required', 'integer'],
            'mood' => ['nullable', 'string', 'max:80'],
            'duration' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'progress' => ['required', 'string', 'max:80'],
            'notes' => ['nullable', 'string', 'max:3000'],
            'message' => ['nullable', 'string', 'max:3000'],
        ]);

        if (!Schema::hasTable('seguimientos')) {
            return redirect()->back()->withInput()->withErrors([
                'error' => 'No existe la tabla seguimientos. Ejecuta las migraciones.',
            ]);
        }

        $existsQuery = DB::table('reservas')
            ->where('id_empleado', (int) $user->id)
            ->where('id_mascota', (int) $validated['pet'])
            ->where('id_actividad', (int) $validated['activity']);

        if (Schema::hasTable('servicios')) {
            $existsQuery->leftJoin('servicios as s', 's.id', '=', 'reservas.id_actividad');
        } elseif (Schema::hasTable('actividades')) {
            $existsQuery->leftJoin('actividades as a', 'a.id_actividad', '=', 'reservas.id_actividad');
        }

        $exists = $existsQuery->exists();

        if (!$exists) {
            return redirect()->back()->withInput()->withErrors([
                'pet' => 'La mascota seleccionada no está asignada a este entrenador.',
            ]);
        }

        $payload = [
            'id_mascota' => (int) $validated['pet'],
            'id_entrenador' => (int) $user->id,
            'id_actividad' => (int) $validated['activity'],
            'estado_animo' => $validated['mood'] ?? null,
            'duracion' => $validated['duration'] ?? null,
            'nivel_progreso' => $validated['progress'],
            'notas' => $validated['notes'] ?? null,
            'mensaje_dueno' => $validated['message'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $columns = Schema::getColumnListing('seguimientos');
        $payload = array_filter(
            $payload,
            fn ($_, $key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );

        DB::table('seguimientos')->insert($payload);

        return redirect()->route('entrenador.seguimiento')->with('success', 'Registro de seguimiento guardado correctamente.');
    }

    public function horario()
    {
        $user = Auth::user();

        // Marcar automáticamente como finalizadas las reservas pasadas
        if (Schema::hasTable('reservas')) {
            DB::table('reservas')
                ->where('id_empleado', $user->id)
                ->where('fecha', '<', now()->toDateString())
                ->where('estado', 'Confirmada')
                ->update(['estado' => 'Finalizada']);
        }

        // Get trainer availability from database
        $availability = DB::table('trainer_availability')
            ->where('trainer_id', $user->id)
            ->get()
            ->keyBy('day_of_week');

        // Build week schedule with availability and reservations
        $days = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
        $dayMap = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miercoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sabado', 0 => 'Domingo'];

        $week = [];

        // Get current week dates
        $startOfWeek = now()->startOfWeek();

        foreach ($days as $index => $dayName) {
            $dayOfWeek = $index === 6 ? 0 : $index + 1; // Convert to MySQL day_of_week (0=Sunday)
            $date = $startOfWeek->copy()->addDays($index);

            // Get availability for this day
            $dayAvail = $availability->get($dayOfWeek);
            $startTime = $dayAvail->start_time ?? '08:00:00';
            $endTime = $dayAvail->end_time ?? '22:00:00';
            $isAvailable = $dayAvail->is_available ?? true;

            // Get reservations for this day
            $reservationIdColumn = Schema::hasColumn('reservas', 'id') ? 'id' : 'id_reserva';

            $reservations = collect();
            // Solo mostrar reservas si la fecha es hoy o futura
            if ($date->greaterThanOrEqualTo(now()->startOfDay())) {
                $reservations = DB::table('reservas')
                    ->where('id_empleado', $user->id)
                    ->where('fecha', $date->format('Y-m-d'))
                    ->whereNotIn('estado', ['Finalizada', 'Cancelada'])
                    ->orderBy($reservationIdColumn)
                    ->get();
            }

            $items = [];
            foreach ($reservations as $r) {
                $items[] = [
                    'time' => '00:00',
                    'pet' => $r->id_mascota ?? 'Mascota',
                    'activity' => $r->id_actividad ?? 'Servicio',
                    'status' => $r->estado ?? 'pendiente',
                ];
            }

            $week[$dayName] = [
                'available' => $isAvailable,
                'start_time' => substr($startTime, 0, 5),
                'end_time' => substr($endTime, 0, 5),
                'items' => $items,
                'date' => $date->format('d/m'),
            ];
        }

        return view('entrenador.horario', [
            'user' => $user,
            'week' => $week,
        ]);
    }

    public function updateAvailability(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'is_available' => ['required', 'boolean'],
        ]);

        // Validate time range is between 8am and 10pm
        $minTime = '08:00';
        $maxTime = '22:00';

        if ($validated['start_time'] < $minTime || $validated['end_time'] > $maxTime) {
            return back()->withErrors(['time' => 'El horario debe estar entre 08:00 y 22:00']);
        }

        DB::table('trainer_availability')->updateOrInsert(
            [
                'trainer_id' => $user->id,
                'day_of_week' => $validated['day_of_week'],
            ],
            [
                'start_time' => $validated['start_time'] . ':00',
                'end_time' => $validated['end_time'] . ':00',
                'is_available' => $validated['is_available'],
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect()->route('entrenador.horario')->with('success', 'Horario actualizado correctamente');
    }

    public function historial()
    {
        $user = Auth::user();

        $records = collect();

        if (Schema::hasTable('reservas')) {
            $hasMascotas = Schema::hasTable('mascotas');
            $mascotaKey = $hasMascotas && Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $hasServicios = Schema::hasTable('servicios');
            $hasActividades = !$hasServicios && Schema::hasTable('actividades');

            $query = DB::table('reservas as r')
                ->where('r.id_empleado', (int) $user->id)
                ->where('r.estado', 'Finalizada');

            if ($hasMascotas) {
                $query->leftJoin('mascotas as m', "m.$mascotaKey", '=', 'r.id_mascota');
            }
            if ($hasServicios) {
                $query->leftJoin('servicios as s', 's.id', '=', 'r.id_actividad');
            } elseif ($hasActividades) {
                $query->leftJoin('actividades as a', 'a.id_actividad', '=', 'r.id_actividad');
            }

            $records = $query
                ->when($hasServicios, fn ($query) => $query->whereIn('s.nombre', ['Entrenamiento Básico', 'Entrenamiento Avanzado']))
                ->when($hasActividades, fn ($query) => $query->whereIn('a.tipo_actividad', ['Entrenamiento Básico', 'Entrenamiento Avanzado']))
                ->orderByDesc('r.fecha')
                ->select([
                    DB::raw('COALESCE(r.fecha, "") as date'),
                    DB::raw($hasMascotas ? 'COALESCE(m.nombre, "") as pet' : '"" as pet'),
                    DB::raw($hasServicios ? 'COALESCE(s.nombre, "") as service' : ($hasActividades ? 'COALESCE(a.tipo_actividad, "") as service' : '"" as service')),
                    DB::raw($hasServicios ? 'COALESCE(s.duracion, "") as duration' : '"" as duration'),
                    DB::raw('COALESCE(r.comentarios, "") as notes'),
                ])
                ->get()
                ->map(fn ($row) => [
                    'date' => $row->date,
                    'pet' => $row->pet,
                    'service' => $row->service,
                    'duration' => $row->duration,
                    'notes' => $row->notes,
                ]);
        }

        return view('entrenador.historial', [
            'user' => $user,
            'records' => $records,
        ]);
    }

    public function reservas()
    {
        $user = Auth::user();
        Log::info('Trainer reservas method called', ['user_id' => $user->id]);

        $reservas = collect();
        $counts = [
            'pendientes' => 0,
            'confirmadas' => 0,
            'canceladas' => 0,
            'total' => 0,
        ];

        if (Schema::hasTable('reservas')) {
            $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';
            $hasMascotas = Schema::hasTable('mascotas');
            $mascotaKey = $hasMascotas && Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $hasServicios = Schema::hasTable('servicios');
            $hasActividades = !$hasServicios && Schema::hasTable('actividades');
            $hasUsers = Schema::hasTable('users');
            $hasHora = Schema::hasColumn('reservas', 'hora');
            $hasComentarios = Schema::hasColumn('reservas', 'comentarios');

            $base = DB::table('reservas as r')->where('r.id_empleado', (int) $user->id);
            if ($hasServicios) {
                $base->leftJoin('servicios as sf', 'sf.id', '=', 'r.id_actividad');
            } elseif ($hasActividades) {
                $base->leftJoin('actividades as af', 'af.id_actividad', '=', 'r.id_actividad');
            }

            $counts = [
                'pendientes' => (clone $base)->whereIn('r.estado', ['Pendiente', 'Pendiente de Evaluación', 'Cita de Evaluación Asignada'])->count(),
                'confirmadas' => (clone $base)->whereIn('r.estado', ['Confirmada', 'Cotizado / Pendiente de Aprobación', 'Aceptada / Esperando Pago', 'Pagada', 'Pagado / En Curso'])->count(),
                'canceladas' => (clone $base)->whereIn('r.estado', ['Cancelada', 'Rechazada por el Cliente'])->count(),
                'total' => (clone $base)->count(),
            ];

            $query = DB::table('reservas as r')->where('r.id_empleado', (int) $user->id);

            if ($hasMascotas) {
                $query->leftJoin('mascotas as m', "m.$mascotaKey", '=', 'r.id_mascota');
            }
            if ($hasServicios) {
                $query->leftJoin('servicios as s', 's.id', '=', 'r.id_actividad');
            } elseif ($hasActividades) {
                $query->leftJoin('actividades as a', 'a.id_actividad', '=', 'r.id_actividad');
            }
            if ($hasMascotas && $hasUsers && Schema::hasColumn('mascotas', 'id_dueno')) {
                $query->leftJoin('users as u', 'u.id', '=', 'm.id_dueno');
            }

            $reservas = $query
                ->orderByDesc("r.$reservaKey")
                ->select([
                    DB::raw("r.$reservaKey as id"),
                    DB::raw($hasMascotas ? 'COALESCE(m.nombre, "") as pet' : '"" as pet'),
                    DB::raw($hasUsers ? 'COALESCE(u.name, "") as owner' : '"" as owner'),
                    DB::raw($hasServicios ? 'COALESCE(s.nombre, "") as service' : ($hasActividades ? 'COALESCE(a.tipo_actividad, "") as service' : '"" as service')),
                    DB::raw('COALESCE(r.fecha, "") as date'),
                    DB::raw($hasHora ? 'COALESCE(r.hora, "") as time' : '"" as time'),
                    DB::raw($hasServicios ? 'COALESCE(s.precio, 0) as price' : '0 as price'),
                    DB::raw('COALESCE(r.estado, "") as status'),
                    DB::raw($hasComentarios ? 'COALESCE(r.comentarios, "") as comments' : '"" as comments'),
                    DB::raw('COALESCE(r.fecha_evaluacion, "") as fecha_evaluacion'),
                    DB::raw('COALESCE(r.hora_evaluacion, "") as hora_evaluacion'),
                    DB::raw('COALESCE(r.precio, 0) as precio_cotizado'),
                    DB::raw('COALESCE(r.duracion, 0) as duracion'),
                    DB::raw('COALESCE(r.observaciones, "") as observaciones'),
                ])
                ->get()
                ->map(function ($r) {
                    $status = mb_strtolower((string) $r->status);
                    Log::info('Reserva status debug', [
                        'id' => $r->id,
                        'original_status' => $r->status,
                        'lowercase_status' => $status,
                    ]);
                    return [
                        'id' => $r->id,
                        'pet' => $r->pet,
                        'owner' => $r->owner,
                        'service' => $r->service,
                        'date' => $r->date,
                        'time' => $r->time,
                        'price' => $r->price,
                        'status' => $status,
                        'comments' => $r->comments,
                        'fecha_evaluacion' => $r->fecha_evaluacion ?? '',
                        'hora_evaluacion' => $r->hora_evaluacion ?? '',
                        'precio_cotizado' => $r->precio_cotizado ?? 0,
                        'duracion' => $r->duracion ?? 0,
                        'observaciones' => $r->observaciones ?? '',
                    ];
                });
        }

        return view('entrenador.reservas', [
            'user' => $user,
            'reservas' => $reservas,
            'counts' => $counts,
        ]);
    }

    public function updateReservaEstado(Request $request, $reserva)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'estado' => ['required', 'string', 'in:Pendiente,Confirmada,Cancelada'],
        ]);

        if (!Schema::hasTable('reservas')) {
            return redirect()->back()->withErrors([
                'error' => 'No existe la tabla reservas.',
            ]);
        }

        $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';

        $row = DB::table('reservas')
            ->where($reservaKey, (int) $reserva)
            ->first();

        if (!$row) {
            return redirect()->back()->withErrors([
                'error' => 'La reserva no existe.',
            ]);
        }

        $payload = [
            'estado' => $validated['estado'],
            'updated_at' => now(),
        ];

        $columns = Schema::getColumnListing('reservas');
        $payload = array_filter(
            $payload,
            fn ($_, $key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );

        DB::table('reservas')->where($reservaKey, (int) $reserva)->update($payload);

        if ($validated['estado'] === 'Confirmada' && Schema::hasTable('notificaciones') && Schema::hasTable('mascotas')) {
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $mascota = DB::table('mascotas')
                ->where($mascotaKey, (int) $row->id_mascota)
                ->first();

            if ($mascota && isset($mascota->id_dueno)) {
                $serviceName = 'servicio';

                if (Schema::hasTable('servicios')) {
                    $serviceName = (string) (DB::table('servicios')
                        ->where('id', (int) $row->id_actividad)
                        ->value('nombre') ?? $serviceName);
                } elseif (Schema::hasTable('actividades')) {
                    $serviceName = (string) (DB::table('actividades')
                        ->where('id_actividad', (int) $row->id_actividad)
                        ->value('tipo_actividad') ?? $serviceName);
                }

                $petName = $mascota->nombre ?? 'tu mascota';
                $date = $row->fecha ?? '';
                $now = now();

                DB::table('notificaciones')->insert([
                    [
                        'user_id' => (int) $mascota->id_dueno,
                        'tipo' => 'cita',
                        'titulo' => 'Tu cita fue aceptada',
                        'mensaje' => "El entrenador aceptó la cita de {$petName} para {$serviceName}" . ($date ? " el {$date}." : '.'),
                        'url' => route('owner.seguimiento'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'user_id' => (int) $mascota->id_dueno,
                        'tipo' => 'pago',
                        'titulo' => 'Pago pendiente',
                        'mensaje' => "Debes realizar el pago de la reserva confirmada de {$petName}.",
                        'url' => route('owner.pagos'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Estado de la reserva actualizado correctamente.');
    }

    /**
     * Asignar cita de evaluación para servicio especial
     */
    public function asignarCitaEvaluacion(Request $request, $reserva)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'fecha_evaluacion' => ['required', 'date', 'after:today'],
            'hora_evaluacion' => ['required', 'date_format:H:i'],
        ]);

        if (!Schema::hasTable('reservas')) {
            return redirect()->back()->withErrors(['error' => 'No existe la tabla reservas.']);
        }

        $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';

        $row = DB::table('reservas')
            ->where($reservaKey, (int) $reserva)
            ->first();

        if (!$row) {
            return redirect()->back()->withErrors(['error' => 'La reserva no existe.']);
        }

        $payload = [
            'fecha_evaluacion' => $validated['fecha_evaluacion'],
            'hora_evaluacion' => $validated['hora_evaluacion'],
            'estado' => 'Cita de Evaluación Asignada',
            'updated_at' => now(),
        ];

        $columns = Schema::getColumnListing('reservas');
        $payload = array_filter(
            $payload,
            fn ($_, $key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );

        DB::table('reservas')->where($reservaKey, (int) $reserva)->update($payload);

        // Enviar notificación al dueño
        if (Schema::hasTable('notificaciones') && Schema::hasTable('mascotas')) {
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $mascota = DB::table('mascotas')
                ->where($mascotaKey, (int) $row->id_mascota)
                ->first();

            if ($mascota && isset($mascota->id_dueno)) {
                try {
                    DB::table('notificaciones')->insert([
                        'user_id' => $mascota->id_dueno,
                        'tipo' => 'cita_evaluacion',
                        'titulo' => 'Cita de Evaluación Asignada',
                        'mensaje' => "Cita de evaluación asignada para el servicio de Formación y Crianza. Fecha: {$validated['fecha_evaluacion']} a las {$validated['hora_evaluacion']}",
                        'url' => '/dashboard/reservas',
                        'leida_en' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error creating notification: ' . $e->getMessage());
                }
            }
        }

        return redirect()->back()->with('success', 'Cita de evaluación asignada exitosamente.');
    }

    /**
     * Registrar diagnóstico y cotización después de la evaluación
     */
    public function registrarDiagnostico(Request $request, $reserva)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'precio' => ['required', 'numeric', 'min:0'],
            'duracion' => ['required', 'integer', 'min:1'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ]);

        if (!Schema::hasTable('reservas')) {
            return redirect()->back()->withErrors(['error' => 'No existe la tabla reservas.']);
        }

        $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';

        $row = DB::table('reservas')
            ->where($reservaKey, (int) $reserva)
            ->first();

        if (!$row) {
            return redirect()->back()->withErrors(['error' => 'La reserva no existe.']);
        }

        $payload = [
            'precio' => $validated['precio'],
            'duracion' => $validated['duracion'],
            'observaciones' => $validated['observaciones'] ?? null,
            'estado' => 'Cotizado / Pendiente de Aprobación',
            'updated_at' => now(),
        ];

        $columns = Schema::getColumnListing('reservas');
        $payload = array_filter(
            $payload,
            fn ($_, $key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );

        DB::table('reservas')->where($reservaKey, (int) $reserva)->update($payload);

        // Enviar notificación al dueño
        if (Schema::hasTable('notificaciones') && Schema::hasTable('mascotas')) {
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $mascota = DB::table('mascotas')
                ->where($mascotaKey, (int) $row->id_mascota)
                ->first();

            if ($mascota && isset($mascota->id_dueno)) {
                try {
                    DB::table('notificaciones')->insert([
                        'user_id' => $mascota->id_dueno,
                        'tipo' => 'cotizacion',
                        'titulo' => 'Cotización Disponible',
                        'mensaje' => "Cotización disponible para el servicio de Formación y Crianza. Precio: {$validated['precio']} COP. Duración: {$validated['duracion']} días.",
                        'url' => '/dashboard/reservas',
                        'leida_en' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error creating notification: ' . $e->getMessage());
                }
            }
        }

        return redirect()->back()->with('success', 'Diagnóstico y cotización registrados exitosamente.');
    }

    public function chat()
    {
        $user = Auth::user();

        $ownerIds = \App\Models\ChatMessage::query()
            ->where('sender_type', 'user')
            ->orderByDesc('created_at')
            ->pluck('sender_id')
            ->unique()
            ->values();

        $owners = Schema::hasTable('users')
            ? DB::table('users')->whereIn('id', $ownerIds)->get()->keyBy('id')
            : collect();

        $activeOwnerId = $ownerIds->first();

        $conversations = $ownerIds->map(function ($ownerId) use ($owners, $activeOwnerId) {
            $owner = $owners->get($ownerId);
            $name = (string) (($owner->name ?? null) ?: 'Dueño');
            $lastMessage = \App\Models\ChatMessage::query()
                ->where(function ($query) use ($ownerId) {
                    $query->where('sender_id', $ownerId)
                        ->orWhere('receiver_id', $ownerId);
                })
                ->orderByDesc('created_at')
                ->first();

            return [
                'initial' => mb_substr($name, 0, 1),
                'name' => $name,
                'subtitle' => (string) ($lastMessage->message ?? ''),
                'active' => (int) $ownerId === (int) $activeOwnerId,
            ];
        })->toArray();

        $activeOwner = $activeOwnerId ? $owners->get($activeOwnerId) : null;
        $active = $activeOwnerId ? [
            'name' => (string) (($activeOwner->name ?? null) ?: 'Dueño'),
            'subtitle' => 'Conversación con dueño',
        ] : null;

        $dbMessages = $activeOwnerId
            ? \App\Models\ChatMessage::query()
                ->where(function ($query) use ($activeOwnerId, $user) {
                    $query->where('sender_id', $activeOwnerId)
                        ->orWhere(function ($query) use ($activeOwnerId, $user) {
                            $query->where('sender_id', $user->id)
                                ->where('receiver_id', $activeOwnerId);
                        });
                })
                ->orderBy('created_at', 'asc')
                ->get()
            : collect();

        $messages = $dbMessages->map(function ($msg) use ($user) {
            return [
                'from' => $msg->sender_id === $user->id ? 'trainer' : 'owner',
                'text' => $msg->message,
            ];
        })->toArray();

        return view('entrenador.chat', [
            'user' => $user,
            'conversations' => $conversations,
            'activeConversation' => $active,
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $ownerId = \App\Models\ChatMessage::query()
            ->where('sender_type', 'user')
            ->orderByDesc('created_at')
            ->value('sender_id');

        // Guardar el mensaje en la base de datos
        \App\Models\ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $ownerId,
            'message' => $validated['message'],
            'sender_type' => 'trainer',
            'is_read' => false,
        ]);

        if ($ownerId && Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')->updateOrInsert([
                'user_id' => (int) $ownerId,
                'tipo' => 'chat',
                'url' => route('owner.chat', [], false),
            ], [
                'user_id' => (int) $ownerId,
                'tipo' => 'chat',
                'titulo' => 'Nuevo mensaje del entrenador',
                'mensaje' => Auth::user()->name . ' te envió un mensaje.',
                'url' => route('owner.chat', [], false),
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

        // Marcar todas como leídas inmediatamente al abrir la sección (Opción A) en ambas tablas
        if (Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')
                ->where('user_id', (int) $user->id)
                ->whereNull('leida_en')
                ->update([
                    'leida_en' => now(),
                ]);
        }
        if (Schema::hasTable('notifications')) {
            DB::table('notifications')
                ->where('id_usuario', (int) $user->id)
                ->where('leido', false)
                ->update([
                    'leido' => true,
                    'leido_en' => now(),
                ]);
        }

        $notifications = collect();

        if (Schema::hasTable('notificaciones')) {
            $spanish = DB::table('notificaciones')
                ->where('user_id', (int) $user->id)
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
                ->where('id_usuario', (int) $user->id)
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

        return view('entrenador.notificaciones', [
            'user' => $user,
            'notifications' => $notifications,
            'unreadCount' => 0, // Todas acaban de ser marcadas como leídas
        ]);
    }

    public function markNotificationAsRead($id)
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

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
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

        return response()->json(['success' => true]);
    }

    public function perfil()
    {
        $user = Auth::user();

        $fullName = trim((string) ($user->name ?? ''));
        $parts = preg_split('/\s+/', $fullName, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $firstName = $parts[0] ?? '';
        $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';

        $profile = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => '',
            'specialty' => '',
            'title' => '',
        ];

        if (Schema::hasTable('empleados')) {
            $employee = DB::table('empleados')
                ->where('id_empleado', (int) $user->id)
                ->first();

            if ($employee) {
                $profile['phone'] = (string) ($employee->telefono ?? '');
                $profile['specialty'] = (string) ($employee->especialidad ?? '');
                $profile['title'] = (string) ($employee->cargo ?? 'Entrenador');
            }
        }

        return view('entrenador.perfil', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function updatePerfil(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'nombre' => ['required', 'string', 'max:255'],
                'apellido' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'telefono' => ['nullable', 'string', 'max:60'],
                'especialidad' => ['nullable', 'string', 'max:255'],
            ]);

            $fullName = trim($validated['nombre'] . ' ' . $validated['apellido']);

            $user->name = $fullName;
            $user->email = $validated['email'];
            $user->save();

            if (Schema::hasTable('empleados')) {
                $cols = Schema::getColumnListing('empleados');

                $payload = [];
                if (in_array('nombre', $cols, true)) {
                    $payload['nombre'] = $fullName;
                }
                if (in_array('cargo', $cols, true)) {
                    $payload['cargo'] = $validated['especialidad'] ?: 'entrenador';
                }
                if (in_array('telefono', $cols, true)) {
                    $payload['telefono'] = $validated['telefono'] ?? null;
                }
                if (in_array('especialidad', $cols, true)) {
                    $payload['especialidad'] = $validated['especialidad'] ?? null;
                }

                if (!empty($payload)) {
                    if (in_array('updated_at', $cols, true)) {
                        $payload['updated_at'] = now();
                    }

                    $exists = DB::table('empleados')->where('id_empleado', (int) $user->id)->exists();

                    if ($exists) {
                        DB::table('empleados')->where('id_empleado', (int) $user->id)->update($payload);
                    } else {
                        $payload['id_empleado'] = $user->id;
                        if (in_array('created_at', $cols, true)) {
                            $payload['created_at'] = now();
                        }
                        DB::table('empleados')->insert($payload);
                    }
                }
            }

            return redirect()->route('entrenador.perfil')->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('entrenador.perfil')->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }
}
