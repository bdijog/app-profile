<?php

namespace Database\Factories;

use App\Enums\MenuLocation;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Menu>
 */
class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'location' => fake()->randomElement([MenuLocation::Header, MenuLocation::Footer, MenuLocation::Sidebar]),
            'is_active' => true,
        ];
    }
}
