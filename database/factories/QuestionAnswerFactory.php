<?php

namespace Database\Factories;

use App\Enums\AnswerStatus;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuestionAnswer>
 */
class QuestionAnswerFactory extends Factory
{
    protected $model = QuestionAnswer::class;

    public function definition(): array
    {
        return [
            'question_id' => Question::factory(),
            'user_id' => User::factory(),
            'body' => '<p>'.fake()->paragraph(2).'</p>',
            'status' => AnswerStatus::Published,
            'is_published' => true,
            'answered_at' => now(),
        ];
    }
}
