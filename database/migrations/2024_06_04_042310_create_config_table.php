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
        Schema::create('config', function (Blueprint $table) {
            $table->id();
            $table->string('last_serial_number', 100)->default('000000000');
            $table->string('serial_port', 100);
            $table->string('label_printer', 100);
            $table->string('report_printer', 100);
            $table->string('image_directory', 250);
            $table->integer('baud_rate')->unsigned();
            $table->integer('data_bits')->unsigned()->default(7);
            $table->integer('stop_bits')->unsigned()->default(1);
            $table->string('parity', 250)->default('none');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};
