<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminServiceController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $hasActive = Schema::hasColumn('servicios', 'activo');

        $serviceRows = Servicio::query()
            ->leftJoin('categorias_servicio as cs', 'servicios.categoria_id', '=', 'cs.id')
            ->select([
                'servicios.*',
                'cs.nombre as categoria_nombre',
            ])
            ->orderByDesc('servicios.id')
            ->get();

        $categoryOptions = DB::table('categorias_servicio')
            ->select(['id', 'nombre'])
            ->orderBy('nombre')
            ->get();

        $categories = $categoryOptions
            ->pluck('nombre')
            ->values();

        $catColor = function (string $cat): string {
            return match (mb_strtolower($cat)) {
                'entrenamiento' => 'purple',
                'formacion trabajo', 'formación trabajo' => 'blue',
                'cuidado y alojamiento' => 'green',
                'actividades' => 'yellow',
                default => 'gray',
            };
        };

        $services = $serviceRows->map(function ($r) use ($catColor) {
            return [
                'id' => $r->id,
                'category_id' => $r->categoria_id,
                'category' => $r->categoria_nombre,
                'category_color' => $catColor((string) $r->categoria_nombre),
                'name' => $r->nombre,
                'description' => $r->descripcion,
                'price' => (int) $r->precio,
                'duration' => $r->duracion,
                'rating' => 0,
                'usage_month' => 0,
                'active' => (bool) ($r->activo ?? 1),
            ];
        })->values();

        $totalServices = $serviceRows->count();
        $activeServices = $hasActive ? $serviceRows->where('activo', 1)->count() : $serviceRows->count();
        $totalUses = 0;
        $estimatedRevenue = 0;

        return view('admin.services.gestionservicios', [
            'admin' => $admin,
            'services' => $services,
            'categories' => collect(['Todos'])->merge($categories)->values(),
            'categoryOptions' => $categoryOptions,
            'stats' => [
                'total_services' => $totalServices,
                'active_services' => $activeServices,
                'total_uses' => $totalUses,
                'estimated_revenue' => $estimatedRevenue,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:1'],
            'category_id' => ['required', 'integer', 'exists:categorias_servicio,id'],
        ]);

        $payload = [
            'nombre' => $data['name'],
            'descripcion' => $data['description'] ?? '',
            'precio' => $data['price'],
            'duracion' => $data['duration'],
            'categoria_id' => $data['category_id'],
        ];

        if (Schema::hasColumn('servicios', 'activo')) {
            $payload['activo'] = 1;
        }

        Servicio::create($payload);

        return redirect()->route('admin.services');
    }

    public function update(Request $request, Servicio $servicio)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:1'],
            'category_id' => ['required', 'integer', 'exists:categorias_servicio,id'],
        ]);

        $servicio->update([
            'nombre' => $data['name'],
            'descripcion' => $data['description'] ?? '',
            'precio' => $data['price'],
            'duracion' => $data['duration'],
            'categoria_id' => $data['category_id'],
        ]);

        return redirect()->route('admin.services')->with('success', 'proceso exitoso');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        return redirect()->route('admin.services')->with('success', 'proceso exitoso');
    }

    public function toggleActive(Request $request, Servicio $servicio)
    {
        $request->validate([
            'active' => ['required', 'boolean'],
        ]);

        if (!Schema::hasColumn('servicios', 'activo')) {
            return response()->json([
                'ok' => true,
                'active' => true,
            ]);
        }

        $servicio->update([
            'activo' => (bool) $request->boolean('active'),
        ]);

        return response()->json([
            'ok' => true,
            'active' => (bool) ($servicio->activo ?? false),
        ]);
    }
}
