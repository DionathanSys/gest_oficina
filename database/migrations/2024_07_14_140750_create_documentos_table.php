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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('nro_documento');
            $table->decimal('valor');
            $table->date('vencimento');
            $table->foreignId('fornecedor_id')->constrained('fornecedores');
            $table->string('modo_envio')->default('malote');
            $table->date('envio')->nullable();
            $table->integer('parcela');
            $table->integer('parcelas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
