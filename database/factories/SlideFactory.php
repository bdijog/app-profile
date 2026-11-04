<?php

namespace Database\Factories;

use App\Models\Slide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Slide>
 */
class SlideFactory extends Factory
{
    protected $model = Slide::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'image' => 'slides/sample.jpg',
            'link_url' => fake()->url(),
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
            'start_date' => now()->toDateString(),
            'end_date' => null,
        ];
    }
}
