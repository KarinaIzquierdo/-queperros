<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('payment_type'); // 'sponsorship' o 'service'
            $table->unsignedBigInteger('paymentable_id');
            $table->string('paymentable_type'); // SponsorDog o ServiceApproval
            $table->string('mercado_pago_id')->nullable(); // ID de la preferencia/pago en MercadoPago
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('COP');
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected', 'cancelled'
            $table->string('payment_method')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['paymentable_id', 'paymentable_type']);
            $table->index('status');
            $table->index('mercado_pago_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
