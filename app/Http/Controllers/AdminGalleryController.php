<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminGalleryController extends Controller
{
    public function publicGallery()
    {
        $directory = 'gallery/public';
        $photos = collect(Storage::disk('public')->files($directory))
            ->filter(fn ($file) => preg_match('/\.(jpe?g|png|webp|gif)$/i', $file))
            ->sortDesc()
            ->map(fn ($file) => [
                'url' => Storage::url($file),
            ])
            ->values();

        return view('galeria', [
            'photos' => $photos,
        ]);
    }

    public function index()
    {
        $directory = 'gallery/public';
        $photos = collect(Storage::disk('public')->files($directory))
            ->filter(fn ($file) => preg_match('/\.(jpe?g|png|webp|gif)$/i', $file))
            ->sortDesc()
            ->map(fn ($file) => [
                'path' => $file,
                'url' => Storage::url($file),
                'name' => basename($file),
            ])
            ->values();

        return view('admin.gallery.index', [
            'photos' => $photos,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        foreach ($request->file('photos') as $photo) {
            $photo->store('gallery/public', 'public');
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Fotos publicadas correctamente.');
    }

    public function destroy($photo)
    {
        $path = 'gallery/public/' . $photo;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return redirect()->route('admin.gallery.index')->with('success', 'Foto eliminada de la galería pública.');
        }

        return redirect()->route('admin.gallery.index')->with('error', 'La foto no existe.');
    }
}
