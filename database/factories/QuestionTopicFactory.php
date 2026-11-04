<?php

namespace Database\Factories;

use App\Models\QuestionTopic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<QuestionTopic>
 */
class QuestionTopicFactory extends Factory
{
    protected $model = QuestionTopic::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'icon' => 'heroicon-o-chat-bubble-bottom-center-text',
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
