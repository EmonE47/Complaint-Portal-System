<?php
// database/migrations/2024_01_01_000001_create_complaints_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            
            // Personal Information
            $table->string('name');
            $table->string('phone_no');
            $table->string('email');
            $table->string('voter_id_number');
            
            // Address Information
            $table->text('permanent_address');
            $table->text('present_address');
            $table->boolean('is_same_address')->default(false);
            
            // Complaint Details
            $table->enum('complaint_type', [
                'lost_item',
                'land_dispute', 
                'harassment',
                'theft',
                'fraud',
                'domestic_violence',
                'public_nuisance',
                'cyber_crime',
                'other'
            ]);
            
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
            ]);
            
            $table->text('description');
            
            // Status and Tracking
            $table->enum('status', [
                'pending',
                'under_investigation', 
                'resolved',
                'rejected',
                'reopened'
            ])->default('pending');
            
            $table->string('complaint_number')->unique()->nullable();
            
            // File Attachments
            $table->json('attachments')->nullable();

            // Additional Fields from Model
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('complainant_name')->nullable();
            $table->string('complainant_contact')->nullable();
            $table->text('incident_location')->nullable();
            $table->timestamp('incident_datetime')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->text('evidence')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key for created_by
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes for better performance
            $table->index('complaint_number');
            $table->index('status');
            $table->index('complaint_type');
            $table->index('police_station');
            $table->index('voter_id_number');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}