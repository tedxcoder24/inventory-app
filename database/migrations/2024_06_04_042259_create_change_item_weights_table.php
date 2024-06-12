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
        Schema::create('change_item_weights', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_time');
            $table->foreignId('operator_id')->constrained();
            $table->morphs('changeable');
            $table->float('gross_weight', 8, 2)->unsigned();
            $table->string('note', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_item_weights');
    }
};
