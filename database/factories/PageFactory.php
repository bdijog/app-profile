<?php

namespace Database\Factories;

use App\Enums\PageStatus;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'user_id' => User::factory(),
            'title' => ucfirst($title),
            'slug' => Str::slug($title),
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(2)).'</p>',
            'image' => null,
            'status' => PageStatus::Published,
            'sort_order' => fake()->numberBetween(1, 10),
            'show_in_menu' => true,
        ];
    }
}
