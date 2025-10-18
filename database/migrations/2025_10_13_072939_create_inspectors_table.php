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
        Schema::create('inspectors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('number');
            $table->string('nid_number')->unique();
            $table->enum('rank', ['inspector', 'si', 'asi']);
            $table->unsignedBigInteger('police_station_id');
            $table->string('password');
            $table->timestamps();

            $table->foreign('police_station_id')->references('id')->on('police_stations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspectors');
    }
};
