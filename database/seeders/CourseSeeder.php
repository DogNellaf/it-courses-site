<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'category' => 'Backend-разработка',
                'title'    => 'PHP с нуля до PRO',
                'duration' => 80,
                'cost'     => 29900.00,
                'description' => 'Полный курс PHP-разработки: от основ языка до создания современных веб-приложений на Laravel. Включает работу с базами данных, REST API и развёртывание на сервере.',
                'image'    => 'images/php.jpg',
            ],
            [
                'category' => 'Backend-разработка',
                'title'    => 'Laravel: профессиональная разработка',
                'duration' => 60,
                'cost'     => 24900.00,
                'description' => 'Углублённый курс по фреймворку Laravel 10: Eloquent ORM, очереди, события, кеширование, тестирование и деплой.',
                'image'    => 'images/laravel.jpg',
            ],
            [
                'category' => 'Frontend-разработка',
                'title'    => 'JavaScript и TypeScript',
                'duration' => 70,
                'cost'     => 27900.00,
                'description' => 'Современный JavaScript ES2023+, TypeScript, асинхронное программирование, работа с DOM и популярными библиотеками.',
                'image'    => 'images/js.jpg',
            ],
            [
                'category' => 'Frontend-разработка',
                'title'    => 'Vue.js 3 — реактивные приложения',
                'duration' => 50,
                'cost'     => 22900.00,
                'description' => 'Создание SPA на Vue 3 с использованием Composition API, Pinia, Vue Router и интеграцией с REST API.',
                'image'    => 'images/vue.jpg',
            ],
            [
                'category' => 'Базы данных',
                'title'    => 'SQL и PostgreSQL для разработчиков',
                'duration' => 40,
                'cost'     => 18900.00,
                'description' => 'Проектирование реляционных баз данных, сложные запросы, индексирование, транзакции и оптимизация производительности.',
                'image'    => 'images/sql.jpg',
            ],
            [
                'category' => 'DevOps',
                'title'    => 'Docker и Kubernetes',
                'duration' => 55,
                'cost'     => 31900.00,
                'description' => 'Контейнеризация приложений, оркестрация, CI/CD пайплайны, мониторинг и управление кластерами Kubernetes.',
                'image'    => 'images/devops.jpg',
            ],
            [
                'category' => 'Тестирование и QA',
                'title'    => 'Тестирование PHP-приложений',
                'duration' => 35,
                'cost'     => 16900.00,
                'description' => 'PHPUnit, Pest, Feature и Unit тесты, TDD, моки, фикстуры и интеграция тестов в CI/CD.',
                'image'    => 'images/testing.jpg',
            ],
            [
                'category' => 'Data Science',
                'title'    => 'Python для анализа данных',
                'duration' => 65,
                'cost'     => 33900.00,
                'description' => 'NumPy, Pandas, Matplotlib, scikit-learn. Обработка и визуализация данных, машинное обучение на практике.',
                'image'    => 'images/python.jpg',
            ],
        ];

        foreach ($courses as $data) {
            $category = Category::where('title', $data['category'])->first();
            if (! $category) {
                continue;
            }

            Course::firstOrCreate(
                ['title' => $data['title']],
                [
                    'duration'    => $data['duration'],
                    'cost'        => $data['cost'],
                    'description' => $data['description'],
                    'image'       => $data['image'],
                    'category_id' => $category->id,
                ]
            );
        }
    }
}
