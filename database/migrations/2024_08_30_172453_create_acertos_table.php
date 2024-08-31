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
        Schema::create('acertos', function (Blueprint $table) {
            $table->id();
            $table->string('fechamento');
            $table->integer('nro_acerto');
            $table->integer('motorista_id');
            $table->string('motorista');
            $table->decimal('vlr_fechamento',10,2);
            $table->decimal('vlr_media',10,2);
            $table->decimal('vlr_inss',10,2);
            $table->decimal('vlr_irrf',10,2);
            $table->decimal('vlr_manutencao',10,2);
            $table->decimal('vlr_diferenca',10,2);
            $table->decimal('vlr_comissao',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acertos');
    }
};
