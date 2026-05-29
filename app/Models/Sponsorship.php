<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    protected $fillable = [
        'user_id',
        'sponsor_dog_id',
        'plan',
        'monto_mensual',
        'estado',
    ];

    protected $casts = [
        'monto_mensual' => 'integer',
    ];
}
