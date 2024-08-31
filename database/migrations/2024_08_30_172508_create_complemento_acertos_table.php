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
        Schema::create('complementos_acerto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acerto_id')->constrained('acertos')->cascadeOnDelete();
            $table->decimal('vlr_ajuda',10,2);
            $table->string('motivo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complementos_acerto');
    }
};
