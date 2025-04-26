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
        Schema::table('ordens_servico', function (Blueprint $table) {
            $table->foreignId('anotacao_veiculo_id')->nullable()->constrained('anotacao_veiculos')->after('veiculo_id');
        });

        Schema::table('item_ordem_servicos', function (Blueprint $table) {
            $table->foreignId('anotacao_veiculo_id')->nullable()->constrained('anotacao_veiculos')->after('ordem_servico_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anotacao_veiculos', function (Blueprint $table) {
            $table->dropForeign(['anotacao_veiculo_id']);
            $table->dropColumn('anotacao_veiculo_id');
        });
    }
};
