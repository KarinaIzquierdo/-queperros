<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_type', // 'sponsorship' o 'service'
        'paymentable_id', // ID del modelo relacionado (SponsorDog o ServiceApproval)
        'paymentable_type', // Tipo del modelo relacionado
        'mercado_pago_id', // ID de la preferencia/pago en MercadoPago
        'amount',
        'currency',
        'status', // 'pending', 'approved', 'rejected', 'cancelled'
        'payment_method',
        'payment_date',
        'metadata', // JSON con información adicional
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'metadata' => 'array',
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación polimórfica con el modelo relacionado (SponsorDog o ServiceApproval)
    public function paymentable()
    {
        return $this->morphTo();
    }

    // Relación con SponsorDog si el pago es de apadrinamiento
    public function sponsorDog()
    {
        return $this->hasOne(SponsorDog::class, 'id', 'paymentable_id')
            ->where('paymentable_type', SponsorDog::class);
    }

    // Relación con ServiceApproval si el pago es de servicio
    public function serviceApproval()
    {
        return $this->hasOne(ServiceApproval::class, 'id', 'paymentable_id')
            ->where('paymentable_type', ServiceApproval::class);
    }
}
