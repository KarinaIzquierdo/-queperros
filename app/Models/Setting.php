<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'nombre_negocio',
        'slogan',
        'direccion',
        'telefono',
        'email',
        'hora_apertura',
        'hora_cierre',
        'atiende_fines_semana',
        'notificaciones_email',
        'notificaciones_sms',
        'notificaciones_push',
        'recordatorio_citas',
        'autenticacion_dos_factores',
        'cierre_sesion_minutos',

        'evento_nuevos_usuarios',
        'evento_citas',
        'evento_alertas_sistema',

        'lunes_activo',
        'lunes_inicio',
        'lunes_fin',
        'martes_activo',
        'martes_inicio',
        'martes_fin',
        'miercoles_activo',
        'miercoles_inicio',
        'miercoles_fin',
        'jueves_activo',
        'jueves_inicio',
        'jueves_fin',
        'viernes_activo',
        'viernes_inicio',
        'viernes_fin',
        'sabado_activo',
        'sabado_inicio',
        'sabado_fin',
        'domingo_activo',
        'domingo_inicio',
        'domingo_fin',
    ];
}
