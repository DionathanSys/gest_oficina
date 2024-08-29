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
        Schema::create('produtos_cotacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotacao_id')->constrained('cotacoes')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            $table->decimal('quantidade');
            $table->string('unidade')->default('PC');
            $table->string('status');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos_cotacoes');
    }
};
