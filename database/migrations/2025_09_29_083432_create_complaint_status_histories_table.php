<?php
// database/migrations/2024_01_01_000002_create_complaint_status_histories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintStatusHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');
            
            $table->enum('status', [
                'pending',
                'under_investigation',
                'resolved', 
                'rejected',
                'reopened'
            ]);
            
            $table->text('remarks')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users');
            
            $table->timestamps();
            
            // Indexes
            $table->index('complaint_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaint_status_histories');
    }
}