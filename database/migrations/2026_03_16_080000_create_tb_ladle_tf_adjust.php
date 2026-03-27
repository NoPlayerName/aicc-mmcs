<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('material-use')->create('tb_ladle_tf_adjust', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date')->index();
            $table->string('materialable_id')->index();
            $table->string('materialable_type')->default('master')->index();
            $table->decimal('qty_adjust', 14, 3);
            $table->text('note')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->index(['transaction_date', 'materialable_id'], 'idx_ladle_tf_adjust_date_material');
        });
    }

    public function down(): void
    {
        Schema::connection('material-use')->dropIfExists('tb_ladle_tf_adjust');
    }
};
