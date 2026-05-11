<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $table = 'mascotas';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'id_dueno',
        'nombre',
        'foto',
        'tipo',
        'raza',
        'edad',
        'peso',
        'sexo',
        'vacunas',
        'fecha_ultima_desparasitacion',
        'fecha_vacuna_tos_perreras',
        'info_adicional',
        'servicio_requerido',
        'nombre_tutor',
        'telefono',
        'estado_actual',
        'notas_adicionales',
    ];
}
