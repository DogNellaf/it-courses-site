<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $titles = [
            'Backend-разработка',
            'Frontend-разработка',
            'Мобильная разработка',
            'DevOps',
            'Data Science',
            'Кибербезопасность',
            'Базы данных',
            'Тестирование',
        ];

        return [
            'title' => $this->faker->unique()->randomElement($titles),
        ];
    }
}
