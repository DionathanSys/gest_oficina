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
        Schema::table('itens_manutencao', function (Blueprint $table) {
            $table->boolean('controla_posicao');
            $table->string('codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itens_manutencao', function (Blueprint $table) {
            $table->dropColumn('controla_posicao');
            $table->dropColumn('codigo');
        });
    }
};
