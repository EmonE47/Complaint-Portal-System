<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PoliceStation;

class PoliceStationFactory extends Factory
{
    protected $model = PoliceStation::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company . ' Police Station',
            'location' => $this->faker->city(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->companyEmail(),
            'address' => $this->faker->address(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
