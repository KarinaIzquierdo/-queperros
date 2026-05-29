<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorDog extends Model
{
    protected $fillable = [
        'nombre',
        'raza',
        'edad',
        'sexo',
        'foto',
        'historia',
        'necesidades',
        'meta_mensual',
        'estado',
        'publicado',
    ];

    protected $casts = [
        'publicado' => 'boolean',
        'meta_mensual' => 'integer',
        'edad' => 'integer',
    ];
}
