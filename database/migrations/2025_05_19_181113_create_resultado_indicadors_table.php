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
        Schema::create('resultados_indicador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gestor_id')->constrained('gestores')->cascadeOnDelete();
            $table->foreignId('indicador_id')->constrained('indicadores')->cascadeOnDelete();
            $table->date('periodo');
            $table->string('resultado');    //! string numero oq vai ser
            $table->decimal('pontuacao_obtida', 3, 0)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados_indicador');
    }
};
