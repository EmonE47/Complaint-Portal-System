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
        Schema::table('case_assignments', function (Blueprint $table) {
            $table->dropForeign(['inspector_id']);
            $table->renameColumn('inspector_id', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_assignments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'inspector_id');
            $table->foreign('inspector_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
