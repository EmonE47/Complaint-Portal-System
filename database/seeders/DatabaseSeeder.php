<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PoliceStation;
use App\Models\Inspector;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintStatusHistory;
use App\Models\CaseAssignment;
use App\Models\Message;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create police stations first so other factories can reference them
        PoliceStation::factory()->count(10)->create();

        // Create users and inspectors
        User::factory()->count(10)->create();
        Inspector::factory()->count(10)->create();

        // Create complaints and related records
        Complaint::factory()->count(10)->create();

        // Ensure each other table has at least 10 records
        ComplaintAttachment::factory()->count(10)->create();
        ComplaintStatusHistory::factory()->count(10)->create();
        CaseAssignment::factory()->count(10)->create();
        Message::factory()->count(10)->create();
        Notification::factory()->count(10)->create();

        // Keep a deterministic test user for login if needed
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
