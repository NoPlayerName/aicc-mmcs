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
        Schema::connection('material-use')->create('tb_charging_head_ace', function (Blueprint $table) {
            $table->id();
            $table->string('plan_id_anchor');
            $table->string('charging');
            $table->string('lot');
            $table->string('model_id');
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('material-use')->dropIfExists('tb_charging_head__ace');
    }
};
