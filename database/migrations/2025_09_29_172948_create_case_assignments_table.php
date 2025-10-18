<?php
// database/migrations/2024_01_01_000005_create_case_assignments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('case_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');
            $table->foreignId('inspector_id')->constrained('users')->onDelete('cascade');
            $table->string('assigned_by'); // Who assigned the case
            $table->text('assignment_notes')->nullable();
            $table->enum('status', ['assigned', 'in_progress', 'completed', 'reassigned'])->default('assigned');
            $table->timestamp('assigned_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('complaint_id');
            $table->index('inspector_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('case_assignments');
    }
}