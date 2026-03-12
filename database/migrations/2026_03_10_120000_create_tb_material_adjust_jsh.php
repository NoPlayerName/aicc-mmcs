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
        Schema::connection('material-use')->create('tb_material_adjust_jsh', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date')->index();
            $table->string('materialable_id')->index();
            $table->string('materialable_type')->default('master')->index();
            $table->decimal('qty_adjust', 14, 3);
            $table->text('note')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->index(['transaction_date', 'materialable_id'], 'idx_adjust_jsh_date_material');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('material-use')->dropIfExists('tb_material_adjust_jsh');
    }
};
