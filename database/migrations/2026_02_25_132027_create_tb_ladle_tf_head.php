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
        Schema::connection('material-use')->create('tb_ladle_tf_head', function (Blueprint $table) {
            $table->id();
            $table->integer('furnace_id');
            $table->string('lot');
            $table->string('product_id');
            $table->tinyInteger('weighing_status');
            $table->tinyInteger('conveyor_drop_status');
            $table->tinyInteger('ladle_drop_status');
            $table->tinyInteger('treatment_duration_check');
            $table->string('molten_weight');
            $table->string('ladle_molten_temp');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('material-use')->dropIfExists('tb_ladle_tf_head');
    }
};
