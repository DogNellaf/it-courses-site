<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $course   = Course::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $course->category);
        $this->assertEquals($category->id, $course->category->id);
    }

    public function test_course_has_many_applications(): void
    {
        $course = Course::factory()->create();
        Application::factory()->count(3)->create(['course_id' => $course->id]);

        $this->assertCount(3, $course->applications);
        $this->assertInstanceOf(Application::class, $course->applications->first());
    }

    public function test_course_cost_is_cast_to_decimal(): void
    {
        $course = Course::factory()->create(['cost' => 29900.50]);

        $this->assertIsString($course->cost); // decimal cast возвращает строку
        $this->assertEquals('29900.50', $course->cost);
    }

    public function test_course_duration_is_cast_to_integer(): void
    {
        $course = Course::factory()->create(['duration' => 80]);

        $this->assertIsInt($course->duration);
    }

    public function test_course_is_fillable(): void
    {
        $category = Category::factory()->create();

        $course = Course::create([
            'title'       => 'Тестовый курс',
            'duration'    => 30,
            'cost'        => 10000.00,
            'description' => 'Описание',
            'image'       => 'images/test.jpg',
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('courses', ['title' => 'Тестовый курс']);
    }

    public function test_deleting_course_cascades_to_applications(): void
    {
        $course = Course::factory()->create();
        Application::factory()->count(2)->create(['course_id' => $course->id]);

        $course->delete();

        $this->assertDatabaseCount('applications', 0);
    }
}
