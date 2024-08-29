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
        Schema::create('propostas_cotacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotacao_id')->constrained('cotacoes')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            $table->foreignId('fornecedor_id')->constrained('fornecedores');
            $table->decimal('valor',10,2);
            $table->string('status');
            $table->string('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propostas_cotacao');
    }
};
