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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_no')->nullable()->after('email');
            $table->string('voter_id_number')->unique()->nullable()->after('phone_no');
            $table->text('address')->nullable()->after('voter_id_number');
            $table->unsignedBigInteger('police_station_id')->nullable()->after('address');
            $table->enum('rank', ['inspector', 'si', 'asi'])->nullable()->after('police_station_id');
            $table->string('nid_number')->unique()->nullable()->after('rank');

            $table->foreign('police_station_id')->references('id')->on('police_stations')->onDelete('set null');
            $table->index('police_station_id');
            $table->index('voter_id_number');
            $table->index('nid_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['police_station_id']);
            $table->dropColumn(['phone_no', 'voter_id_number', 'address', 'police_station_id', 'rank', 'nid_number']);
        });
    }
};
