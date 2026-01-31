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
        Schema::connection('material-use')->create('tb_charging_head_jsh', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id_anchor')->index();
            $table->smallInteger('charging')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('material-use')->dropIfExists('tb_charging_head');
    }
};
