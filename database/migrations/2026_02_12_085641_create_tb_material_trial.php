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
        Schema::connection('master')->create('tb_material_trial', function (Blueprint $table) {
            $table->string('material_code')->primary();
            $table->string('material_name');
            $table->string('unit')->nullable();
            $table->tinyInteger('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('master')->dropIfExists('tb_material_trial');
    }
};
