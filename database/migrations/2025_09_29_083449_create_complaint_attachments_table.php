<?php
// database/migrations/2024_01_01_000003_create_complaint_attachments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');
            
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type');
            $table->unsignedInteger('file_size');
            $table->string('original_name');
            
            $table->timestamps();
            
            // Indexes
            $table->index('complaint_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaint_attachments');
    }
}