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
        Schema::create('anotacao_veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veiculo_id')->constrained('veiculos');
            $table->foreignId('item_manutencao_id')->nullable()->constrained('itens_manutencao');
            $table->string('observacao')->nullable();
            $table->date('data_referencia');
            $table->string('tipo_anotacao');
            $table->string('status');
            $table->string('prioridade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anotacao_veiculos');
    }
};
