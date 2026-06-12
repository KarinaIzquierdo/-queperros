<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dueno extends Model
{
    protected $table = 'duenos';

    protected $primaryKey = 'id_dueno';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_dueno',
        'nombre',
        'telefono',
        'documento',
        'direccion',
        'ciudad',
        'fecha_nacimiento',
    ];
}
