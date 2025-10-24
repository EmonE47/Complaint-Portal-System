<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Complaint;
use App\Models\User;
use App\Models\PoliceStation;

class ComplaintFactory extends Factory
{
    protected $model = Complaint::class;

    public function definition(): array
    {
    $userId = User::query()->count() ? User::all()->random()->id : null;
    $stationId = PoliceStation::query()->count() ? PoliceStation::all()->random()->id : null;

        $types = array_keys(Complaint::COMPLAINT_TYPES);
        $statuses = array_keys(Complaint::STATUSES);

        return [
            'name' => $this->faker->name(),
            'phone_no' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'voter_id_number' => (string) $this->faker->numerify('VOTER-#####'),
            'permanent_address' => $this->faker->address(),
            'present_address' => $this->faker->address(),
            'is_same_address' => $this->faker->boolean(70),
            'complaint_type' => $this->faker->randomElement($types),
            'police_station_id' => $stationId,
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement($statuses),
            'priority' => $this->faker->randomElement(['low','medium','high']),
            'complainant_name' => $this->faker->name(),
            'complainant_contact' => $this->faker->phoneNumber(),
            'incident_location' => $this->faker->address(),
            'incident_datetime' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'created_by' => $userId,
            'evidence' => null,
        ];
    }
}
