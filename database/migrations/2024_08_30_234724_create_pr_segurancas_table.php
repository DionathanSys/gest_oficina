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
        Schema::create('pr_segurancas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acerto_id')->constrained('acertos')->cascadeOnDelete();
            $table->decimal('premio', 10, 2);
            $table->integer('ev_freadas')->nullable();
            $table->integer('ev_forca_g')->nullable();
            $table->integer('ev_velocidade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_segurancas');
    }
};
