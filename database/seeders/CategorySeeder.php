<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Backend-разработка',
            'Frontend-разработка',
            'Мобильная разработка',
            'DevOps',
            'Data Science',
            'Кибербезопасность',
            'Базы данных',
            'Тестирование и QA',
        ];

        foreach ($categories as $title) {
            Category::firstOrCreate(['title' => $title]);
        }
    }
}
