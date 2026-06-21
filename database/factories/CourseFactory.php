<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(3, false),
            'duration'    => $this->faker->numberBetween(10, 200),
            'cost'        => $this->faker->randomFloat(2, 1000, 100000),
            'description' => $this->faker->paragraphs(3, true),
            'image'       => 'images/' . $this->faker->image('public/images', 640, 480, null, false),
            'category_id' => Category::factory(),
        ];
    }
}
