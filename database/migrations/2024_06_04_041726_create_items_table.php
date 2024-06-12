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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained();
            $table->timestamp('date_time');
            $table->integer('serial_number')->unsigned();
            $table->foreignId('item_type_id')->constrained();
            $table->string('batch_id', 25);
            $table->string('metrc_id', 25);
            $table->float('tare_weight', 8, 2)->unsigned();
            $table->float('gross_weight', 8, 2)->unsigned();
            $table->foreignId('weight_unit_id')->constrained();
            $table->foreignId('strain_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('color_id')->constrained();
            $table->foreignId('clarity_id')->constrained();
            $table->foreignId('appearance_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
