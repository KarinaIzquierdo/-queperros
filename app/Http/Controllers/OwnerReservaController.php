<?php

namespace App\Http\Controllers;

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
            'profesional_id' => ['required', 'integer'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'comentarios' => ['nullable', 'string', 'max:3000'],
            'precio_estimado' => ['nullable', 'string', 'max:60'],
        ]);

        if (!Schema::hasTable('mascotas') || !Schema::hasColumn('mascotas', 'id_dueno')) {
            return redirect()->back()->withInput()->withErrors([
                'mascota_id' => 'No se pudo validar la mascota. Verifica la estructura de la base de datos.',
            ]);
        }

        $pet = DB::table('mascotas')
            ->where('id', (int) $validated['mascota_id'])
            ->where('id_dueno', (int) $user->id)
            ->first();

        if (!$pet) {
            return redirect()->back()->withInput()->withErrors([
                'mascota_id' => 'La mascota seleccionada no te pertenece o no existe.',
            ]);
        }

        if (!Schema::hasTable('servicios')) {
            return redirect()->back()->withInput()->withErrors([
                'servicio_id' => 'No se pudo validar el servicio. Verifica la estructura de la base de datos.',
            ]);
        }

        $serviceExists = DB::table('servicios')
            ->where('id', (int) $validated['servicio_id'])
            ->exists();

        if (!$serviceExists) {
            return redirect()->back()->withInput()->withErrors([
                'servicio_id' => 'El servicio seleccionado no existe.',
            ]);
        }

        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'rol_id')) {
            return redirect()->back()->withInput()->withErrors([
                'profesional_id' => 'No se pudo validar el entrenador. Verifica la estructura de la base de datos.',
            ]);
        }

        $trainerId = (int) $validated['profesional_id'];
        
        // Verificar que el usuario existe y es entrenador
        $trainer = DB::table('users')
            ->where('id', $trainerId)
            ->where('rol_id', 3)
            ->first();

        if (!$trainer) {
            return redirect()->back()->withInput()->withErrors([
                'profesional_id' => 'El entrenador seleccionado no es valido.',
            ]);
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
            'mascota_id' => (int) $validated['mascota_id'],
            'servicio_id' => (int) $validated['servicio_id'],
            'profesional_id' => (int) $validated['profesional_id'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'estado' => 'pendiente',
            'comentarios' => $validated['comentarios'] ?? null,
            'precio_estimado' => $precio,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $columns = Schema::getColumnListing('reservas');
        $data = array_filter(
            $data,
            fn ($_, $key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );

        DB::table('reservas')->insert($data);

        return redirect()->route('owner.reservas')->with('success', 'Reserva creada correctamente.');
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

        $row = DB::table('reservas as r')
            ->join('mascotas as m', 'm.id', '=', 'r.mascota_id')
            ->where('r.id', (int) $reserva)
            ->where('m.id_dueno', (int) $user->id)
            ->select(['r.id', 'r.estado'])
            ->first();

        if (!$row) {
            return redirect()->back()->withErrors([
                'error' => 'La reserva no existe o no te pertenece.',
            ]);
        }

        if ((string) $row->estado !== 'pendiente') {
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

        DB::table('reservas')->where('id', (int) $row->id)->update($payload);

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

        $row = DB::table('reservas as r')
            ->join('mascotas as m', 'm.id', '=', 'r.mascota_id')
            ->where('r.id', (int) $reserva)
            ->where('m.id_dueno', (int) $user->id)
            ->select(['r.id', 'r.estado'])
            ->first();

        if (!$row) {
            return redirect()->back()->withErrors([
                'error' => 'La reserva no existe o no te pertenece.',
            ]);
        }

        if ((string) $row->estado !== 'pendiente') {
            return redirect()->back()->withErrors([
                'error' => 'Solo puedes cancelar reservas en estado pendiente.',
            ]);
        }

        $payload = [
            'estado' => 'cancelado',
            'updated_at' => now(),
        ];

        $columns = Schema::getColumnListing('reservas');
        $payload = array_filter(
            $payload,
            fn ($_, $key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_BOTH
        );

        DB::table('reservas')->where('id', (int) $row->id)->update($payload);

        return redirect()->route('owner.reservas')->with('success', 'Reserva cancelada correctamente.');
    }
}
