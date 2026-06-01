<?php

namespace App\Http\Controllers;

use App\Models\ServiceApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OwnerReservaController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'servicio_id' => ['required', 'integer'],
            'mascota_id' => ['required', 'integer'],
            'profesional_id' => ['nullable', 'integer'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'comentarios' => ['nullable', 'string', 'max:3000'],
            'precio_estimado' => ['nullable', 'string', 'max:60'],
        ]);

        \Log::info('Datos recibidos en reserva:', $validated);

        if (!Schema::hasTable('mascotas') || !Schema::hasColumn('mascotas', 'id_dueno')) {
            return redirect()->back()->withInput()->withErrors([
                'mascota_id' => 'No se pudo validar la mascota. Verifica la estructura de la base de datos.',
            ]);
        }

        $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';

        $pet = DB::table('mascotas')
            ->where($mascotaKey, (int) $validated['mascota_id'])
            ->where('id_dueno', (int) $user->id)
            ->first();

        if (!$pet) {
            return redirect()->back()->withInput()->withErrors([
                'mascota_id' => 'La mascota seleccionada no te pertenece o no existe.',
            ]);
        }

        $hasServicios = Schema::hasTable('servicios');
        $hasActividades = !$hasServicios && Schema::hasTable('actividades');

        if (!$hasServicios && !$hasActividades) {
            return redirect()->back()->withInput()->withErrors([
                'servicio_id' => 'No se pudo validar el servicio. Verifica la estructura de la base de datos.',
            ]);
        }

        // Obtener información del servicio para determinar si es de entrenamiento
        $service = null;
        if ($hasServicios) {
            $service = DB::table('servicios')
                ->where('id', (int) $validated['servicio_id'])
                ->first();
        }

        if (!$service) {
            return redirect()->back()->withInput()->withErrors([
                'servicio_id' => 'El servicio seleccionado no existe.',
            ]);
        }

        // Verificar si el servicio es de entrenamiento
        $isTrainingService = false;
        $isSpecialService = false; // Servicio especial que requiere evaluación
        if ($service && Schema::hasTable('categorias_servicio')) {
            $category = DB::table('categorias_servicio')
                ->where('id', $service->categoria_id)
                ->first();

            $isTrainingService = $category && strtolower($category->nombre) === 'entrenamiento';
            
            // Verificar si es el servicio especial de Formación y Crianza
            $isSpecialService = $isTrainingService && stripos($service->nombre, 'Formación y Crianza') !== false;
        }

        // Log para depuración
        \Log::info('Service check - Service ID: ' . $service->id . ', Category: ' . ($category ? $category->nombre : 'null'));
        \Log::info('Is training service: ' . ($isTrainingService ? 'true' : 'false'));
        \Log::info('Is special service (Formación y Crianza): ' . ($isSpecialService ? 'true' : 'false'));

        // Si no es un servicio de entrenamiento, crear solicitud de aprobación
        if (!$isTrainingService) {
            \Log::info('Creating service approval for non-training service');
            return $this->createServiceApproval($validated, $user, $service);
        }

        // Si es entrenamiento, continuar con el flujo normal de reservas
        \Log::info('Creating training reservation');
        return $this->createTrainingReservation($validated, $user, $service, $isSpecialService);
    }

    private function createServiceApproval($validated, $user, $service)
    {
        \Log::info('createServiceApproval called with data:', [
            'user_id' => $user->id,
            'mascota_id' => $validated['mascota_id'],
            'servicio_id' => $validated['servicio_id'],
            'fecha' => $validated['fecha'],
            'comentarios' => $validated['comentarios'] ?? null
        ]);

        // Crear solicitud de aprobación
        $approval = ServiceApproval::create([
            'id_usuario' => $user->id,
            'id_mascota' => $validated['mascota_id'],
            'id_servicio' => $validated['servicio_id'],
            'fecha_solicitada' => $validated['fecha'],
            'notas_cliente' => $validated['comentarios'] ?? null,
            'estado' => 'pendiente',
        ]);

        \Log::info('Service approval created with ID: ' . $approval->id);

        // Crear notificación para el admin
        if (Schema::hasTable('notifications')) {
            \Log::info('Creating notification for admin');
            $adminId = DB::table('users')->where('rol', 'admin')->first()->id ?? 1;
            \Log::info('Admin ID found: ' . $adminId);
            
            $mascotaNombre = $approval->mascota->nombre ?? 'una mascota';
            
            try {
                DB::table('notifications')->insert([
                    'id_usuario' => $adminId,
                    'tipo' => 'nueva_aprobacion_servicio',
                    'mensaje' => "Nueva solicitud de servicio: '{$service->nombre}' para {$mascotaNombre}",
                    'url' => '/admin/approvals',
                    'leido' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                \Log::info('Notification created successfully');
            } catch (\Exception $e) {
                \Log::error('Error creating notification: ' . $e->getMessage());
            }
        } else {
            \Log::info('Notifications table does not exist');
        }

        return redirect()->route('owner.services.my')->with('success', 'Solicitud de servicio enviada para aprobación. Puedes ver el estado en la pestaña de Mis Servicios.');
    }

    private function createTrainingReservation($validated, $user, $service, $isSpecialService = false)
    {
        // Continuar con el flujo original para servicios de entrenamiento
        if (Schema::hasTable('actividades')) {
            DB::table('actividades')->updateOrInsert(
                ['id_actividad' => (int) $validated['servicio_id']],
                [
                    'tipo_actividad' => (string) $service->nombre,
                    'horario' => '08:00-18:00',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Para servicios de entrenamiento, validar que se haya seleccionado un entrenador
        if (empty($validated['profesional_id'])) {
            return redirect()->back()->withInput()->withErrors([
                'profesional_id' => 'Debes seleccionar un entrenador para los servicios de entrenamiento.',
            ]);
        }

        $trainerId = (int) $validated['profesional_id'];
        
        // Verificar que el usuario existe y es entrenador
        $trainer = DB::table('users')
            ->where('id', $trainerId)
            ->where('rol', 'entrenador')
            ->first();

        if (!$trainer) {
            return redirect()->back()->withInput()->withErrors([
                'profesional_id' => 'El entrenador seleccionado no es valido.',
            ]);
        }

        if (Schema::hasTable('empleados')) {
            DB::table('empleados')->updateOrInsert(
                ['id_empleado' => $trainerId],
                [
                    'nombre' => (string) ($trainer->name ?? 'Entrenador'),
                    'cargo' => 'entrenador',
                    'turno' => 'diurno',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        if (!Schema::hasTable('reservas')) {
            return redirect()->back()->withInput()->withErrors([
                'error' => 'No existe la tabla reservas. Ejecuta las migraciones para poder guardar reservas.',
            ]);
        }

        $precio = null;
        $rawPrice = trim((string) ($validated['precio_estimado'] ?? ''));
        if ($rawPrice !== '' && mb_strtolower($rawPrice) !== 'consultar') {
            $digits = preg_replace('/[^0-9]/', '', $rawPrice);
            if ($digits !== '') {
                $precio = (float) $digits;
            }
        }

        $data = [
            'id_mascota' => (int) $validated['mascota_id'],
            'id_actividad' => (int) $validated['servicio_id'],
            'id_empleado' => (int) $validated['profesional_id'],
            'fecha' => $validated['fecha'],
            'estado' => $isSpecialService ? 'Pendiente de Evaluación' : 'Pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $columns = Schema::getColumnListing('reservas');
        \Log::info('Columnas de reservas:', $columns);
        $data = array_filter(
            $data,
            fn ($_, $key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );

        \Log::info('Datos a insertar:', $data);

        try {
            DB::table('reservas')->insert($data);
            \Log::info('Reserva insertada correctamente');
        } catch (\Exception $e) {
            \Log::error('Error al insertar reserva:', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->withErrors([
                'error' => 'Error al guardar la reserva: ' . $e->getMessage(),
            ]);
        }

        return redirect()->route('owner.reservas')->with('success', 'Reserva de entrenamiento creada correctamente.');
    }

    /**
     * Aceptar cotización del servicio especial
     */
    public function aceptarCotizacion($reserva)
    {
        $user = Auth::user();

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

        // Verificar que la reserva pertenezca al usuario
        if (Schema::hasTable('mascotas')) {
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $mascota = DB::table('mascotas')
                ->where($mascotaKey, (int) $row->id_mascota)
                ->first();

            if (!$mascota || $mascota->id_dueno != $user->id) {
                return redirect()->back()->withErrors(['error' => 'No tienes permiso para modificar esta reserva.']);
            }
        }

        $payload = [
            'cliente_aceptado' => true,
            'estado' => 'Aceptada / Esperando Pago',
            'updated_at' => now(),
        ];

        $columns = Schema::getColumnListing('reservas');
        $payload = array_filter(
            $payload,
            fn ($_, $key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );

        DB::table('reservas')->where($reservaKey, (int) $reserva)->update($payload);

        // Enviar notificación al entrenador
        if (Schema::hasTable('notificaciones') && isset($row->id_empleado)) {
            try {
                DB::table('notificaciones')->insert([
                    'user_id' => $row->id_empleado,
                    'tipo' => 'cotizacion_aceptada',
                    'titulo' => 'Cotización Aceptada',
                    'mensaje' => "El cliente ha aceptado la cotización del servicio. Esperando pago.",
                    'url' => '/entrenador/reservas',
                    'leida_en' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                \Log::error('Error creating notification: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Cotización aceptada. Ahora puedes proceder con el pago.');
    }

    /**
     * Rechazar cotización del servicio especial
     */
    public function rechazarCotizacion($reserva)
    {
        $user = Auth::user();

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

        // Verificar que la reserva pertenezca al usuario
        if (Schema::hasTable('mascotas')) {
            $mascotaKey = Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $mascota = DB::table('mascotas')
                ->where($mascotaKey, (int) $row->id_mascota)
                ->first();

            if (!$mascota || $mascota->id_dueno != $user->id) {
                return redirect()->back()->withErrors(['error' => 'No tienes permiso para modificar esta reserva.']);
            }
        }

        $payload = [
            'cliente_aceptado' => false,
            'estado' => 'Rechazada por el Cliente',
            'updated_at' => now(),
        ];

        $columns = Schema::getColumnListing('reservas');
        $payload = array_filter(
            $payload,
            fn ($_, $key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );

        DB::table('reservas')->where($reservaKey, (int) $reserva)->update($payload);

        return redirect()->back()->with('success', 'Cotización rechazada. El proceso ha sido cancelado.');
    }

    public function update(Request $request, $reserva)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'comentarios' => ['nullable', 'string', 'max:3000'],
            'precio_estimado' => ['nullable', 'string', 'max:60'],
        ]);

        if (!Schema::hasTable('reservas') || !Schema::hasTable('mascotas') || !Schema::hasColumn('mascotas', 'id_dueno')) {
            return redirect()->back()->withErrors([
                'error' => 'No se pudo validar la reserva. Verifica la estructura de la base de datos.',
            ]);
        }

        $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';

        $row = DB::table('reservas as r')
            ->join('mascotas as m', 'm.id', '=', 'r.id_mascota')
            ->where("r.$reservaKey", (int) $reserva)
            ->where('m.id_dueno', (int) $user->id)
            ->select(["r.$reservaKey as id", 'r.estado'])
            ->first();

        if (!$row) {
            return redirect()->back()->withErrors([
                'error' => 'La reserva no existe o no te pertenece.',
            ]);
        }

        if (mb_strtolower((string) $row->estado) !== 'pendiente') {
            return redirect()->back()->withErrors([
                'error' => 'Solo puedes modificar reservas en estado pendiente.',
            ]);
        }

        $precio = null;
        $rawPrice = trim((string) ($validated['precio_estimado'] ?? ''));
        if ($rawPrice !== '' && mb_strtolower($rawPrice) !== 'consultar') {
            $digits = preg_replace('/[^0-9]/', '', $rawPrice);
            if ($digits !== '') {
                $precio = (float) $digits;
            }
        }

        $payload = [
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'comentarios' => $validated['comentarios'] ?? null,
            'precio_estimado' => $precio,
            'updated_at' => now(),
        ];

        $columns = Schema::getColumnListing('reservas');
        $payload = array_filter(
            $payload,
            fn ($_, $key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );

        DB::table('reservas')->where($reservaKey, (int) $row->id)->update($payload);

        return redirect()->route('owner.reservas')->with('success', 'Reserva modificada correctamente.');
    }

    public function cancel(Request $request, $reserva)
    {
        $user = Auth::user();

        if (!Schema::hasTable('reservas') || !Schema::hasTable('mascotas') || !Schema::hasColumn('mascotas', 'id_dueno')) {
            return redirect()->back()->withErrors([
                'error' => 'No se pudo validar la reserva. Verifica la estructura de la base de datos.',
            ]);
        }

        $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';

        $row = DB::table('reservas as r')
            ->join('mascotas as m', 'm.id', '=', 'r.id_mascota')
            ->where("r.$reservaKey", (int) $reserva)
            ->where('m.id_dueno', (int) $user->id)
            ->select(["r.$reservaKey as id", 'r.estado'])
            ->first();

        if (!$row) {
            return redirect()->back()->withErrors([
                'error' => 'La reserva no existe o no te pertenece.',
            ]);
        }

        if (mb_strtolower((string) $row->estado) !== 'pendiente') {
            return redirect()->back()->withErrors([
                'error' => 'Solo puedes cancelar reservas en estado pendiente.',
            ]);
        }

        $payload = [
            'estado' => 'Cancelada',
            'updated_at' => now(),
        ];

        $columns = Schema::getColumnListing('reservas');
        $payload = array_filter(
            $payload,
            fn ($_, $key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );

        DB::table('reservas')->where($reservaKey, (int) $row->id)->update($payload);

        return redirect()->route('owner.reservas')->with('success', 'Reserva cancelada correctamente.');
    }
}
