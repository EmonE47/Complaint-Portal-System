<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Inspector;
use App\Models\PoliceStation;

class InspectorFactory extends Factory
{
    protected $model = Inspector::class;

    public function definition(): array
    {
    $station = PoliceStation::all()->count() ? PoliceStation::all()->random()->id : null;
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'number' => $this->faker->phoneNumber(),
            'nid_number' => (string) $this->faker->unique()->numerify('############'),
            'rank' => $this->faker->randomElement(['Inspector', 'Sub-Inspector', 'Assistant Inspector']),
            'police_station_id' => $station,
            'password' => Hash::make('password'),
        ];
    }
}
