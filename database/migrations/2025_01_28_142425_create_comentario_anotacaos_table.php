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
        Schema::create('comentario_anotacaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anotacao_veiculo_id')->constrained('anotacao_veiculos');
            $table->foreignId('user_id')->constrained('users');
            $table->text('comentario');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentario_anotacaos');
    }
};
