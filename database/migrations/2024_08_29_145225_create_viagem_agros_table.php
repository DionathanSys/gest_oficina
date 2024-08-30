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
        Schema::create('viagens_agro', function (Blueprint $table) {
            $table->id();
            $table->string('referencia');
            $table->string('fechamento');
            $table->string('nro_viagem');
            $table->string('nro_nota')->unique();
            $table->date('data');
            $table->string('placa',7);
            $table->decimal('km',10,2);
            $table->decimal('frete',10,2);
            $table->string('destino');
            $table->string('local');
            $table->decimal('vlr_cte',10,2);
            $table->decimal('vlr_nfs',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viagens_agro');
    }
};
