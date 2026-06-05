<?php

namespace App\Http\Controllers;

use App\Models\SponsorDog;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminSponsorDogController extends Controller
{
    public function index()
    {
        $dogs = SponsorDog::query()->orderByDesc('id')->get();
        $sponsorships = Sponsorship::query()
            ->leftJoin('users', 'sponsorships.user_id', '=', 'users.id')
            ->select('sponsorships.*', 'users.name as user_name', 'users.email as user_email')
            ->orderByDesc('sponsorships.created_at')
            ->get();

        return view('admin.padrinos.index', [
            'admin' => Auth::user(),
            'dogs' => $dogs,
            'sponsorships' => $sponsorships,
        ]);
    }

    public function store(Request $request)
    {
        \Log::info('SponsorDog store - Request data:', $request->all());
        \Log::info('SponsorDog store - Has file:', ['has_file' => $request->hasFile('foto')]);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'raza' => ['nullable', 'string', 'max:255'],
            'edad' => ['nullable', 'integer', 'min:0', 'max:30'],
            'sexo' => ['nullable', 'string', 'max:50'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'historia' => ['nullable', 'string', 'max:5000'],
            'necesidades' => ['nullable', 'string', 'max:500'],
            'meta_mensual' => ['nullable', 'integer', 'min:0'],
            'estado' => ['nullable', 'string', 'max:80'],
            'publicado' => ['nullable', 'boolean'],
        ]);

        \Log::info('SponsorDog store - Validation passed:', $validated);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('plan-padrino', 'public');
            \Log::info('SponsorDog store - Photo stored:', ['path' => $validated['foto']]);
        }

        $validated['publicado'] = $request->boolean('publicado', true);
        $validated['estado'] = $validated['estado'] ?? 'Disponible';

        \Log::info('SponsorDog store - Final data before create:', $validated);

        $dog = SponsorDog::create($validated);

        \Log::info('SponsorDog store - Dog created:', ['id' => $dog->id, 'nombre' => $dog->nombre]);

        return redirect()->route('admin.planpadrino')->with('status', 'Perrito publicado correctamente.');
    }

    public function update(Request $request, SponsorDog $dog)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'raza' => ['nullable', 'string', 'max:255'],
            'edad' => ['nullable', 'integer', 'min:0', 'max:30'],
            'sexo' => ['nullable', 'string', 'max:50'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'historia' => ['nullable', 'string', 'max:5000'],
            'necesidades' => ['nullable', 'string', 'max:500'],
            'meta_mensual' => ['nullable', 'integer', 'min:0'],
            'estado' => ['nullable', 'string', 'max:80'],
            'publicado' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('foto')) {
            if ($dog->foto && Storage::disk('public')->exists($dog->foto)) {
                Storage::disk('public')->delete($dog->foto);
            }
            $validated['foto'] = $request->file('foto')->store('plan-padrino', 'public');
        }

        $validated['publicado'] = $request->boolean('publicado');
        $dog->update($validated);

        return redirect()->route('admin.planpadrino')->with('status', 'Publicación actualizada correctamente.');
    }

    public function destroy(SponsorDog $dog)
    {
        if ($dog->foto && Storage::disk('public')->exists($dog->foto)) {
            Storage::disk('public')->delete($dog->foto);
        }

        $dog->delete();

        return redirect()->route('admin.planpadrino')->with('status', 'Publicación eliminada correctamente.');
    }
}
