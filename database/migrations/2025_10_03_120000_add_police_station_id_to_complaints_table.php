<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPoliceStationIdToComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // Add police_station_id column
            $table->unsignedBigInteger('police_station_id')->after('complaint_type');

            // Add foreign key constraint
            $table->foreign('police_station_id')->references('id')->on('police_stations')->onDelete('cascade');

            // Drop the old police_station enum column
            $table->dropColumn('police_station');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // Add back the police_station enum column
            $table->enum('police_station', [
                'khulna_sadar',
                'sonadanga',
                'khalishpur',
                'daulatpur',
                'khan_jahan_ali',
                'terokhada',
                'dumuria',
                'phultala',
                'rupsha',
                'botiaghata'
            ])->after('complaint_type');

            // Drop foreign key and column police_station_id
            $table->dropForeign(['police_station_id']);
            $table->dropColumn('police_station_id');
        });
    }
}
