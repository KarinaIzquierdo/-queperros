<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first() ?: new Setting([
            'nombre_negocio' => 'Mas Que Perros',
            'slogan' => 'Tu perro feliz, tu tranquilo',
            'notificaciones_email' => 1,
            'notificaciones_sms' => 0,
            'notificaciones_push' => 0,
            'recordatorio_citas' => 1,
            'evento_nuevos_usuarios' => 1,
            'evento_citas' => 1,
            'evento_alertas_sistema' => 0,
            'cierre_sesion_minutos' => 120,

            'lunes_activo' => 1,
            'lunes_inicio' => '08:00:00',
            'lunes_fin' => '18:00:00',
            'martes_activo' => 1,
            'martes_inicio' => '08:00:00',
            'martes_fin' => '18:00:00',
            'miercoles_activo' => 1,
            'miercoles_inicio' => '08:00:00',
            'miercoles_fin' => '18:00:00',
            'jueves_activo' => 1,
            'jueves_inicio' => '08:00:00',
            'jueves_fin' => '18:00:00',
            'viernes_activo' => 1,
            'viernes_inicio' => '08:00:00',
            'viernes_fin' => '18:00:00',
            'sabado_activo' => 1,
            'sabado_inicio' => '09:00:00',
            'sabado_fin' => '14:00:00',
            'domingo_activo' => 0,
        ]);
        
        return view('admin.settings.configuracion', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nombre_negocio' => 'nullable|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'cierre_sesion_minutos' => 'nullable|integer|min:1',

            'lunes_inicio' => 'nullable',
            'lunes_fin' => 'nullable',
            'martes_inicio' => 'nullable',
            'martes_fin' => 'nullable',
            'miercoles_inicio' => 'nullable',
            'miercoles_fin' => 'nullable',
            'jueves_inicio' => 'nullable',
            'jueves_fin' => 'nullable',
            'viernes_inicio' => 'nullable',
            'viernes_fin' => 'nullable',
            'sabado_inicio' => 'nullable',
            'sabado_fin' => 'nullable',
            'domingo_inicio' => 'nullable',
            'domingo_fin' => 'nullable',
        ]);

        $settings = Setting::first() ?: new Setting();
        
        $checkboxes = [
            'atiende_fines_semana', 
            'notificaciones_email', 
            'notificaciones_sms', 
            'notificaciones_push',
            'recordatorio_citas', 
            'evento_nuevos_usuarios',
            'evento_citas',
            'evento_alertas_sistema',

            'lunes_activo',
            'martes_activo',
            'miercoles_activo',
            'jueves_activo',
            'viernes_activo',
            'sabado_activo',
            'domingo_activo',
        ];

        foreach ($checkboxes as $field) {
            $validated[$field] = $request->has($field) ? 1 : 0;
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()->back()->with('success', 'Ajustes actualizados correctamente.');
    }
}
