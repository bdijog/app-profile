<?php

namespace Database\Factories;

use App\Models\Faq;
use App\Models\QuestionTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            'topic_id' => QuestionTopic::factory(),
            'question' => fake()->sentence().'?',
            'answer' => '<p>'.fake()->paragraph(2).'</p>',
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
