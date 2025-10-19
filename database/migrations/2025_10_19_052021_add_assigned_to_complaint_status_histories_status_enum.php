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
        DB::statement("ALTER TABLE complaint_status_histories MODIFY COLUMN status ENUM('pending', 'assigned', 'under_investigation', 'resolved', 'rejected', 'reopened') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE complaint_status_histories MODIFY COLUMN status ENUM('pending', 'under_investigation', 'resolved', 'rejected', 'reopened') DEFAULT 'pending'");
    }
};
