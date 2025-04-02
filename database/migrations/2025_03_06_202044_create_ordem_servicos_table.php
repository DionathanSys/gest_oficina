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
        Schema::create('ordens_servico', function (Blueprint $table) {
            $table->id();
            $table->string('nro_ordem')->nullable();
            $table->foreignId('veiculo_id')->constrained('veiculos');
            $table->string('tipo_manutencao');
            $table->string('status');
            $table->string('status_sankhya')->nullable();
            $table->string('nro_requisicao')->nullable();
            $table->date('data_abertura')->nullable();
            $table->date('data_encerramento')->nullable();
            $table->date('data_execucao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordens_servico');
    }
};
