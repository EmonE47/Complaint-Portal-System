<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ComplaintAttachment;
use App\Models\Complaint;

class ComplaintAttachmentFactory extends Factory
{
    protected $model = ComplaintAttachment::class;

    public function definition(): array
    {
        $complaintId = Complaint::query()->count() ? Complaint::all()->random()->id : null;

        return [
            'complaint_id' => $complaintId,
            'file_name' => $this->faker->word() . '.jpg',
            'file_path' => 'attachments/' . $this->faker->uuid() . '.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => $this->faker->numberBetween(1024, 204800),
            'original_name' => $this->faker->word() . '.jpg',
        ];
    }
}
