<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
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
            $pets = DB::table('mascotas')
                ->where('id_dueno', (int) $user->id)
                ->select(['id', 'nombre'])
                ->orderBy('nombre')
                ->get();
        }

        $trainers = collect();
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'rol_id')) {
            $trainers = DB::table('users')
                ->where('rol_id', 3)
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
}
