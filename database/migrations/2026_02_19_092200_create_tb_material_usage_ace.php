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
        Schema::connection('material-use')->create('tb_material_usage_ace', function (Blueprint $table) {
            $table->id();
            $table->integer('charging_head_id')->nullable()->index();
            // $table->string('material_id')->nullable()->index();
            // --- TULIS MANUAL AGAR AMAN ---
            // Kolom untuk ID Material (String)
            $table->string('materialable_id')->nullable()->index();
            // Kolom untuk Tipe Model (master/trial)
            $table->string('materialable_type')->nullable()->index();
            $table->float('weight')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->tinyInteger('type_additive')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('material-use')->dropIfExists('tb_material_usage_ace');
    }
};
