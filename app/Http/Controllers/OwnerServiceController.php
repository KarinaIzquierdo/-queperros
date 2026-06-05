<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\ServiceApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OwnerServiceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $pets = collect();
        if (Schema::hasTable('mascotas') && Schema::hasColumn('mascotas', 'id_dueno')) {
            $petIdColumn = Schema::hasColumn('mascotas', 'id') ? 'id' : 'id_mascota';

            $pets = DB::table('mascotas')
                ->where('id_dueno', (int) $user->id)
                ->select([$petIdColumn . ' as id', 'nombre'])
                ->orderBy('nombre')
                ->get();
        }

        $trainers = collect();
        if (Schema::hasTable('users')) {
            $trainers = DB::table('users')
                ->where('rol', 'entrenador')
                ->select(['id', 'name'])
                ->orderBy('name')
                ->get();
        }

        $search = trim((string) $request->query('q', ''));
        $categoryId = trim((string) $request->query('categoria', ''));

        $hasActive = Schema::hasColumn('servicios', 'activo');

        $servicesQuery = Servicio::query()
            ->leftJoin('categorias_servicio as cs', 'servicios.categoria_id', '=', 'cs.id')
            ->select([
                'servicios.*',
                'cs.nombre as categoria_nombre',
            ]);

        if ($hasActive) {
            $servicesQuery->where('servicios.activo', 1);
        }

        if ($categoryId !== '' && $categoryId !== 'all') {
            $servicesQuery->where('servicios.categoria_id', (int) $categoryId);
        }

        if ($search !== '') {
            $servicesQuery->where(function ($q) use ($search) {
                $q->where('servicios.nombre', 'like', '%' . $search . '%')
                    ->orWhere('servicios.descripcion', 'like', '%' . $search . '%');
            });
        }

        $serviceRows = $servicesQuery
            ->orderByDesc('servicios.id')
            ->get();

        $categoryOptions = DB::table('categorias_servicio')
            ->select(['id', 'nombre'])
            ->orderBy('nombre')
            ->get();

        $catColor = function (string $cat): string {
            return match (mb_strtolower(trim($cat))) {
                'entrenamiento canino', 'entrenamiento' => 'purple',
                'formación y crianza', 'formacion y crianza', 'formacion trabajo', 'formación trabajo' => 'slate',
                'cuidado y alojamiento' => 'blue',
                'otras actividades', 'actividades' => 'yellow',
                default => 'gray',
            };
        };

        $services = $serviceRows->map(function ($r) use ($catColor) {
            $catName = (string) ($r->categoria_nombre ?? '');

            return [
                'id' => $r->id,
                'category_id' => $r->categoria_id,
                'category' => $catName,
                'category_color' => $catColor($catName),
                'name' => $r->nombre,
                'description' => (string) ($r->descripcion ?? ''),
                'incluye' => (string) ($r->incluye ?? ''),
                'ideal_para' => (string) ($r->ideal_para ?? ''),
                'price' => $r->precio,
                'duration' => $r->duracion,
            ];
        })->values();

        return view('dueños.servicios', [
            'user' => $user,
            'services' => $services,
            'categoryOptions' => $categoryOptions,
            'search' => $search,
            'activeCategory' => $categoryId,
            'pets' => $pets,
            'trainers' => $trainers,
        ]);
    }

    public function myServices()
    {
        $user = Auth::user();
        
        \Log::info('myServices called for user ID: ' . $user->id);

        // Obtener solicitudes de aprobación de servicios
        $serviceApprovals = ServiceApproval::with(['servicio', 'mascota'])
            ->where('id_usuario', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        \Log::info('Found ' . $serviceApprovals->count() . ' service approvals for user');

        // Obtener reservas de entrenamiento
        $trainingReservations = collect();
        if (Schema::hasTable('reservas')) {
            $reservaKey = Schema::hasColumn('reservas', 'id_reserva') ? 'id_reserva' : 'id';
            $hasMascotas = Schema::hasTable('mascotas');
            $mascotaKey = $hasMascotas && Schema::hasColumn('mascotas', 'id_mascota') ? 'id_mascota' : 'id';
            $hasServicios = Schema::hasTable('servicios');
            $hasUsers = Schema::hasTable('users');

            $query = DB::table('reservas as r')
                ->join('mascotas as m', "m.$mascotaKey", '=', 'r.id_mascota')
                ->where('m.id_dueno', $user->id);

            if ($hasServicios) {
                $query->leftJoin('servicios as s', 's.id', '=', 'r.id_actividad');
            }

            if ($hasUsers && Schema::hasColumn('reservas', 'id_empleado')) {
                $query->leftJoin('users as t', 't.id', '=', 'r.id_empleado');
            }

            $trainingReservations = $query
                ->orderByDesc("r.$reservaKey")
                ->select([
                    DB::raw("r.$reservaKey as id"),
                    'r.fecha',
                    'r.estado',
                    DB::raw($hasMascotas ? 'm.nombre as mascota_nombre' : '"Mascota" as mascota_nombre'),
                    DB::raw($hasServicios ? 'COALESCE(s.nombre, "Entrenamiento") as servicio_nombre' : '"Entrenamiento" as servicio_nombre'),
                    DB::raw($hasUsers ? 'COALESCE(t.name, "Entrenador") as trainer_name' : '"Entrenador" as trainer_name'),
                ])
                ->get();
        }

        return view('dueños.mis-servicios', [
            'user' => $user,
            'serviceApprovals' => $serviceApprovals,
            'trainingReservations' => $trainingReservations,
        ]);
    }

    public function processPayment(Request $request, ServiceApproval $approval)
    {
        $user = Auth::user();

        // Verificar que la aprobación pertenece al usuario
        if ($approval->id_usuario !== $user->id) {
            return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        // Verificar que esté aprobado
        if ($approval->estado !== 'aprobado') {
            return redirect()->back()->with('error', 'Esta solicitud no está aprobada para pago.');
        }

        $request->validate([
            'payment_method' => ['required', 'string'],
            'payment_notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Cambiar estado a "pagado" directamente cuando el cliente paga
        $approval->update([
            'estado' => 'pagado',
            'fecha_pago' => now(),
        ]);

        // Crear la reserva real automáticamente en el sistema
        if (Schema::hasTable('reservas')) {
            DB::table('reservas')->insert([
                'id_mascota' => $approval->id_mascota,
                'id_empleado' => null, // Se puede asignar después
                'id_actividad' => $approval->id_servicio,
                'fecha' => $approval->fecha_solicitada,
                'estado' => 'Confirmada',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Notificar al administrador que el pago se ha realizado
        if (Schema::hasTable('notifications')) {
            $service = DB::table('servicios')->where('id', $approval->id_servicio)->first();
            $mascota = DB::table('mascotas')->where('id_mascota', $approval->id_mascota)->first();
            $adminId = DB::table('users')->where('rol', 'admin')->first()->id ?? 1;
            
            DB::table('notifications')->insert([
                'id_usuario' => $adminId,
                'tipo' => 'pago_confirmado',
                'mensaje' => "El cliente ha pagado el servicio '{$service->nombre}' para {$mascota->nombre}.",
                'url' => '/admin/approvals',
                'leido' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('owner.services.my')
            ->with('success', '¡Pago realizado con éxito! Tu reserva ya está confirmada.');
    }
}
