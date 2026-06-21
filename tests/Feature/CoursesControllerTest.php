<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursesControllerTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────
    // index
    // ──────────────────────────────────────────────

    public function test_index_returns_200(): void
    {
        $response = $this->get(route('index'));

        $response->assertOk();
        $response->assertViewIs('index');
    }

    public function test_index_passes_courses_and_categories_to_view(): void
    {
        $category = Category::factory()->create();
        Course::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->get(route('index'));

        $response->assertViewHas('courses');
        $response->assertViewHas('categories');
    }

    public function test_index_filters_by_category(): void
    {
        $cat1 = Category::factory()->create();
        $cat2 = Category::factory()->create();

        Course::factory()->count(2)->create(['category_id' => $cat1->id]);
        Course::factory()->count(3)->create(['category_id' => $cat2->id]);

        $response = $this->get(route('index', ['category' => $cat1->id]));

        $response->assertOk();
        $courses = $response->viewData('courses');
        $this->assertCount(2, $courses);
        $courses->each(fn ($c) => $this->assertEquals($cat1->id, $c->category_id));
    }

    public function test_index_without_category_paginates_9_per_page(): void
    {
        $category = Category::factory()->create();
        Course::factory()->count(12)->create(['category_id' => $category->id]);

        $response = $this->get(route('index'));

        $courses = $response->viewData('courses');
        $this->assertEquals(9, $courses->perPage());
    }

    // ──────────────────────────────────────────────
    // detail
    // ──────────────────────────────────────────────

    public function test_detail_returns_200_for_existing_course(): void
    {
        $course = Course::factory()->create();

        $response = $this->get(route('detail', $course));

        $response->assertOk();
        $response->assertViewIs('detail');
        $response->assertViewHas('course', fn ($c) => $c->id === $course->id);
    }

    public function test_detail_returns_404_for_missing_course(): void
    {
        $response = $this->get(route('detail', 9999));

        $response->assertNotFound();
    }

    // ──────────────────────────────────────────────
    // storeApplication
    // ──────────────────────────────────────────────

    public function test_store_application_creates_record_and_redirects(): void
    {
        $course = Course::factory()->create();

        $response = $this->post(route('application.store'), [
            'fio'    => 'Иванов Иван Иванович',
            'email'  => 'ivan@example.com',
            'course' => $course->id,
        ]);

        $response->assertRedirect(route('index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('applications', [
            'full_name' => 'Иванов Иван Иванович',
            'email'     => 'ivan@example.com',
            'course_id' => $course->id,
            'status'    => 'pending',
        ]);
    }

    public function test_store_application_validates_required_fields(): void
    {
        $response = $this->post(route('application.store'), []);

        $response->assertSessionHasErrors(['fio', 'email', 'course']);
    }

    public function test_store_application_validates_email_format(): void
    {
        $course = Course::factory()->create();

        $response = $this->post(route('application.store'), [
            'fio'    => 'Тест',
            'email'  => 'not-an-email',
            'course' => $course->id,
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_store_application_rejects_nonexistent_course(): void
    {
        $response = $this->post(route('application.store'), [
            'fio'    => 'Тест',
            'email'  => 'test@example.com',
            'course' => 9999,
        ]);

        $response->assertSessionHasErrors(['course']);
    }

    public function test_store_application_validates_max_length(): void
    {
        $course = Course::factory()->create();

        $response = $this->post(route('application.store'), [
            'fio'    => str_repeat('А', 151),
            'email'  => 'test@example.com',
            'course' => $course->id,
        ]);

        $response->assertSessionHasErrors(['fio']);
    }
}
