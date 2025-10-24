<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CaseAssignment;
use App\Models\Complaint;
use App\Models\User;

class CaseAssignmentFactory extends Factory
{
    protected $model = CaseAssignment::class;

    public function definition(): array
    {
        $complaintId = Complaint::query()->count() ? Complaint::all()->random()->id : null;
        $userId = User::query()->count() ? User::all()->random()->id : null;
        $assignedBy = User::query()->count() ? User::all()->random()->id : null;
        $statuses = array_keys(CaseAssignment::STATUSES);

        $status = $this->faker->randomElement($statuses);
        $assignedAt = $this->faker->dateTimeBetween('-6 months', 'now');
        $completedAt = in_array($status, ['completed']) ? $this->faker->dateTimeBetween($assignedAt, 'now') : null;

        return [
            'complaint_id' => $complaintId,
            'user_id' => $userId,
            'assigned_by' => $assignedBy,
            'assignment_notes' => $this->faker->sentence(),
            'status' => $status,
            'assigned_at' => $assignedAt,
            'completed_at' => $completedAt,
        ];
    }
}
