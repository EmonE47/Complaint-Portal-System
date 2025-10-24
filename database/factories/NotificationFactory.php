<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Notification;
use App\Models\User;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $userId = User::query()->count() ? User::all()->random()->id : null;

        return [
            'user_id' => $userId,
            'type' => $this->faker->randomElement(['info', 'warning', 'alert']),
            'data' => ['message' => $this->faker->sentence()],
            'read_at' => $this->faker->boolean(60) ? $this->faker->dateTimeBetween('-2 months', 'now') : null,
        ];
    }
}
