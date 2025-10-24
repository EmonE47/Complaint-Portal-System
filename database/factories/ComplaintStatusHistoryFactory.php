<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ComplaintStatusHistory;
use App\Models\Complaint;
use App\Models\User;

class ComplaintStatusHistoryFactory extends Factory
{
    protected $model = ComplaintStatusHistory::class;

    public function definition(): array
    {
        $complaintId = Complaint::query()->count() ? Complaint::all()->random()->id : null;
        $userId = User::query()->count() ? User::all()->random()->id : null;
        $statuses = array_keys(\App\Models\Complaint::STATUSES);

        return [
            'complaint_id' => $complaintId,
            'status' => $this->faker->randomElement($statuses),
            'remarks' => $this->faker->sentence(),
            'updated_by' => $userId,
        ];
    }
}
