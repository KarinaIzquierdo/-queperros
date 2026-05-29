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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->string('tipo', 50);
            $table->text('mensaje');
            $table->string('url')->nullable();
            $table->boolean('leido')->default(false);
            $table->timestamp('leido_en')->nullable();
            $table->timestamps();
            
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->index('id_usuario');
            $table->index('tipo');
            $table->index('leido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
