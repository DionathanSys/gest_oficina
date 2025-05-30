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
        Schema::table('indicators', function (Blueprint $table) {
            $table->string('periodicidade')->default('MENSAL')->after('peso'); 
        });

        Schema::create('indicator_result', function (Blueprint $table) {
            $table->decimal('peso', 3, 0)->default(0)->after('resultado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->dropColumn('periodicidade'); 
        });
    }
};
