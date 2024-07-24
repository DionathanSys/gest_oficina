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
        Schema::create('parceiros', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cadastro_unico');
            $table->enum('tipo',['fisica', 'juridica'])->default('juridica');
            $table->enum('vinculo', ['cliente', 'fornecedor', 'colaborador'])->default('cliente');
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->unique('nome');
            $table->unique('cadastro_unico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parceiros');
    }
};
