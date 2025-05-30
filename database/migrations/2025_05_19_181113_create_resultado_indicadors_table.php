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
        Schema::create('indicator_result', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('managers')->cascadeOnDelete();
            $table->foreignId('indicator_id')->constrained('indicators')->cascadeOnDelete();
            $table->date('periodo');
            $table->string('resultado');
            $table->string('periodicidade');
            $table->decimal('peso', 3, 0)->default(0);
            $table->decimal('pontuacao_obtida', 3, 0)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicator_result');
    }
};
