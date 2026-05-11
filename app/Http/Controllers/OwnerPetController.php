<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OwnerPetController extends Controller
{
    /**
     * Mostrar las mascotas del dueño
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $search = trim((string) $request->query('q', ''));

        // Obtener mascotas del usuario actual
        $petsQuery = Mascota::query()->where('id_dueno', (int) $user->id);
        
        if ($search !== '') {
            $petsQuery->where(function ($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('raza', 'like', '%' . $search . '%');
            });
        }

        $pets = $petsQuery->orderBy('nombre')->get();

        return view('dueños.misperros', [
            'user' => $user,
            'pets' => $pets,
            'search' => $search,
        ]);
    }

    /**
     * Registrar una nueva mascota
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // El id_dueno se asigna automáticamente del usuario autenticado por seguridad.
        // No se incluye en el request->validate() para evitar manipulación externa.
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'tipo' => ['nullable', 'string', 'max:50'],
            'raza' => ['required', 'string', 'max:255'],
            'edad' => ['nullable', 'integer', 'min:0', 'max:50'],
            'peso' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'sexo' => ['nullable', 'string', 'max:50'],
            'foto' => ['nullable', 'image', 'max:2048'],

            'vacunas_list' => ['nullable', 'array'],
            'fecha_ultima_desparasitacion' => ['nullable', 'date'],
            'fecha_vacuna_tos_perreras' => ['nullable', 'date'],

            'info_adicional' => ['nullable', 'string', 'max:3000'],
            'servicio_requerido' => ['nullable', 'string', 'max:255'],
            'estado_actual' => ['nullable', 'string', 'max:255'],
            'notas_adicionales' => ['nullable', 'string', 'max:255'],
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pets', 'public');
        }

        // Procesar lista de vacunas a string para la DB
        $vacunasString = '';
        if ($request->has('vacunas_list')) {
            $vacunasString = implode(', ', $request->input('vacunas_list'));
        }

        $data = [
            'id_dueno' => (int) $user->id,
            'nombre' => $validated['nombre'],
            'tipo' => (string) ($validated['tipo'] ?? 'Perro'),
            'raza' => $validated['raza'],
            'edad' => $validated['edad'] ?? null,
            'peso' => $validated['peso'] ?? null,
            'sexo' => $validated['sexo'] ?? null,
            'foto' => $fotoPath,

            'vacunas' => $vacunasString,
            'fecha_ultima_desparasitacion' => $validated['fecha_ultima_desparasitacion'] ?? null,
            'fecha_vacuna_tos_perreras' => $validated['fecha_vacuna_tos_perreras'] ?? null,

            'info_adicional' => $validated['info_adicional'] ?? null,
            'servicio_requerido' => $validated['servicio_requerido'] ?? null,
            'estado_actual' => $validated['estado_actual'] ?? null,
            'notas_adicionales' => $validated['notas_adicionales'] ?? null,
            'nombre_tutor' => (string) ($user->name ?? ''),
        ];

        // Filtrar solo las columnas que existen en la tabla
        $columns = Schema::getColumnListing('mascotas');
        $filtered = [];
        foreach ($data as $k => $v) {
            if (in_array($k, $columns, true)) {
                $filtered[$k] = $v;
            }
        }

        try {
            DB::table('mascotas')->insert($filtered);
            
            Log::info('Mascota registrada exitosamente', [
                'user_id' => $user->id,
                'mascota_nombre' => $validated['nombre'],
                'data' => $filtered
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Error al registrar mascota', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'data' => $filtered
            ]);

            // Eliminar foto si se subió y hubo error
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            return redirect()
                ->route('owner.pets')
                ->withErrors(['store' => 'No se pudo registrar la mascota: ' . $e->getMessage()]);
        }

        return redirect()
            ->route('owner.pets')
            ->with('success', 'Mascota registrada correctamente');
    }

    /**
     * Mostrar detalles de una mascota específica
     */
    public function show(Mascota $mascota)
    {
        $user = Auth::user();
        
        // Verificar que la mascota pertenezca al usuario actual
        if ($mascota->id_dueno !== $user->id) {
            abort(403, 'No tienes permiso para ver esta mascota');
        }

        return view('dueños.detalle-mascota', [
            'user' => $user,
            'pet' => $mascota
        ]);
    }

    /**
     * Mostrar formulario para editar una mascota
     */
    public function edit(Mascota $mascota)
    {
        $user = Auth::user();
        
        // Verificar que la mascota pertenezca al usuario actual
        if ($mascota->id_dueno !== $user->id) {
            abort(403, 'No tienes permiso para editar esta mascota');
        }

        return view('dueños.editar-mascota', [
            'user' => $user,
            'pet' => $mascota
        ]);
    }

    /**
     * Actualizar una mascota existente
     */
    public function update(Request $request, Mascota $mascota)
    {
        $user = Auth::user();
        
        // Verificar que la mascota pertenezca al usuario actual
        if ($mascota->id_dueno !== $user->id) {
            abort(403, 'No tienes permiso para actualizar esta mascota');
        }

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'tipo' => ['nullable', 'string', 'max:50'],
            'raza' => ['required', 'string', 'max:255'],
            'edad' => ['nullable', 'integer', 'min:0', 'max:50'],
            'peso' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'sexo' => ['nullable', 'string', 'max:50'],
            'foto' => ['nullable', 'image', 'max:2048'],

            'vacunas_list' => ['nullable', 'array'],
            'fecha_ultima_desparasitacion' => ['nullable', 'date'],
            'fecha_vacuna_tos_perreras' => ['nullable', 'date'],

            'info_adicional' => ['nullable', 'string', 'max:3000'],
            'servicio_requerido' => ['nullable', 'string', 'max:255'],
            'estado_actual' => ['nullable', 'string', 'max:255'],
            'notas_adicionales' => ['nullable', 'string', 'max:255'],
        ]);

        $fotoPath = $mascota->foto;
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($mascota->foto && Storage::disk('public')->exists($mascota->foto)) {
                Storage::disk('public')->delete($mascota->foto);
            }
            $fotoPath = $request->file('foto')->store('pets', 'public');
        }

        // Procesar lista de vacunas a string para la DB
        $vacunasString = '';
        if ($request->has('vacunas_list')) {
            $vacunasString = implode(', ', $request->input('vacunas_list'));
        }

        $data = [
            'nombre' => $validated['nombre'],
            'tipo' => (string) ($validated['tipo'] ?? 'Perro'),
            'raza' => $validated['raza'],
            'edad' => $validated['edad'] ?? null,
            'peso' => $validated['peso'] ?? null,
            'sexo' => $validated['sexo'] ?? null,
            'foto' => $fotoPath,

            'vacunas' => $vacunasString,
            'fecha_ultima_desparasitacion' => $validated['fecha_ultima_desparasitacion'] ?? null,
            'fecha_vacuna_tos_perreras' => $validated['fecha_vacuna_tos_perreras'] ?? null,

            'info_adicional' => $validated['info_adicional'] ?? null,
            'servicio_requerido' => $validated['servicio_requerido'] ?? null,
            'estado_actual' => $validated['estado_actual'] ?? null,
            'notas_adicionales' => $validated['notas_adicionales'] ?? null,
        ];

        // Filtrar solo las columnas que existen en la tabla
        $columns = Schema::getColumnListing('mascotas');
        $filtered = [];
        foreach ($data as $k => $v) {
            if (in_array($k, $columns, true)) {
                $filtered[$k] = $v;
            }
        }

        try {
            DB::table('mascotas')
                ->where('id', $mascota->id)
                ->update($filtered);
                
            Log::info('Mascota actualizada exitosamente', [
                'user_id' => $user->id,
                'mascota_id' => $mascota->id,
                'data' => $filtered
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Error al actualizar mascota', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'mascota_id' => $mascota->id
            ]);

            return redirect()
                ->route('owner.pets')
                ->withErrors(['update' => 'No se pudo actualizar la mascota: ' . $e->getMessage()]);
        }

        return redirect()
            ->route('owner.pets')
            ->with('success', 'Mascota actualizada correctamente');
    }

    /**
     * Eliminar una mascota
     */
    public function destroy(Mascota $mascota)
    {
        $user = Auth::user();
        
        // Verificar que la mascota pertenezca al usuario actual
        if ($mascota->id_dueno !== $user->id) {
            abort(403, 'No tienes permiso para eliminar esta mascota');
        }

        $foto = $mascota->foto;

        try {
            $mascota->delete();
            
            // Eliminar foto si existe
            if ($foto && Storage::disk('public')->exists($foto)) {
                Storage::disk('public')->delete($foto);
            }
            
            Log::info('Mascota eliminada exitosamente', [
                'user_id' => $user->id,
                'mascota_id' => $mascota->id,
                'mascota_nombre' => $mascota->nombre
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Error al eliminar mascota', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'mascota_id' => $mascota->id
            ]);

            return redirect()
                ->route('owner.pets')
                ->withErrors(['delete' => 'No se pudo eliminar la mascota: ' . $e->getMessage()]);
        }

        return redirect()
            ->route('owner.pets')
            ->with('success', 'Mascota eliminada correctamente');
    }
}
