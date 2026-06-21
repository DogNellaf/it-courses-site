<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_many_courses(): void
    {
        $category = Category::factory()->create();
        Course::factory()->count(4)->create(['category_id' => $category->id]);

        $this->assertCount(4, $category->courses);
        $this->assertInstanceOf(Course::class, $category->courses->first());
    }

    public function test_category_is_fillable(): void
    {
        $category = Category::create(['title' => 'Backend-разработка']);

        $this->assertDatabaseHas('categories', ['title' => 'Backend-разработка']);
    }

    public function test_deleting_category_cascades_to_courses(): void
    {
        $category = Category::factory()->create();
        Course::factory()->count(3)->create(['category_id' => $category->id]);

        $category->delete();

        $this->assertDatabaseCount('courses', 0);
    }
}
