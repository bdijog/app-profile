<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'file_name' => fake()->word().'.jpg',
            'file_path' => 'uploads/'.fake()->uuid().'.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => fake()->numberBetween(1024, 2048000),
            'alt_text' => fake()->sentence(),
            'disk' => 'public',
        ];
    }
}
