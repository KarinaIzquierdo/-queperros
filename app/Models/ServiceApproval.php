<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceApproval extends Model
{
    use HasFactory;

    protected $table = 'service_approvals';

    protected $fillable = [
        'id_usuario',
        'id_mascota',
        'id_servicio',
        'fecha_solicitada',
        'estado',
        'notas_admin',
        'notas_cliente',
        'fecha_aprobacion',
        'fecha_pago',
    ];

    protected $casts = [
        'fecha_solicitada' => 'date',
        'fecha_aprobacion' => 'datetime',
        'fecha_pago' => 'datetime',
    ];

    // Relationships
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeApproved($query)
    {
        return $query->where('estado', 'aprobado');
    }

    public function scopeRejected($query)
    {
        return $query->where('estado', 'rechazado');
    }

    public function scopePaid($query)
    {
        return $query->where('estado', 'pagado');
    }
}
