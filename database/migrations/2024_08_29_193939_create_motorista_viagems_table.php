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
        Schema::create('motoristas_viagem', function (Blueprint $table) {
            $table->id();
            $table->string('fechamento');
            $table->foreignId('viagem_agro_id')->constrained('viagens_agro')->cascadeOnDelete();
            $table->string('nro_nota');
            $table->foreignId('motorista_id')->constrained('motoristas');
            $table->string('motorista');
            $table->foreignId('motorista_dupla_id')->nullable()->constrained('motoristas');
            $table->string('dupla')->nullable();
            $table->string('frete',10,2);
            $table->decimal('comissao');
            $table->decimal('vlr_comissao', 9, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motoristas_viagem');
    }
};
