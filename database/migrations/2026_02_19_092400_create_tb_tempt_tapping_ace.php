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
        Schema::connection('material-use')->create('tb_tempt_tapping_ace', function (Blueprint $table) {
            $table->id();
            $table->integer('charging_head_id')->nullable()->index();
            $table->string('temperatur')->nullable();
            $table->tinyInteger('type_tapping');
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('material-use')->dropIfExists('tb_tempt_tapping_ace');
    }
};
