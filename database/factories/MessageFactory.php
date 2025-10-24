<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Message;
use App\Models\Complaint;
use App\Models\User;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $complaintId = Complaint::query()->count() ? Complaint::all()->random()->id : null;
        $sender = User::query()->count() ? User::all()->random() : null;
        $receiver = User::query()->count() ? User::all()->random() : null;

        if ($sender && $receiver && $sender->id === $receiver->id) {
            $others = User::all()->where('id', '!=', $sender->id);
            $receiver = $others->count() ? $others->random() : null;
        }

        $statuses = array_keys(Message::STATUSES);

        return [
            'complaint_id' => $complaintId,
            'sender_id' => $sender?->id,
            'receiver_id' => $receiver?->id,
            'message' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement($statuses),
            'read_at' => $this->faker->boolean(50) ? $this->faker->dateTimeBetween('-1 months', 'now') : null,
        ];
    }
}
