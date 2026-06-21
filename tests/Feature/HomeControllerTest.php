<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsUser(): User
    {
        return User::factory()->create();
    }

    // ──────────────────────────────────────────────
    // Доступ без авторизации
    // ──────────────────────────────────────────────

    public function test_guest_is_redirected_from_home_index(): void
    {
        $this->get(route('home.index'))->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_home_create(): void
    {
        $this->get(route('home.course.create'))->assertRedirect(route('login'));
    }

    // ──────────────────────────────────────────────
    // index
    // ──────────────────────────────────────────────

    public function test_authenticated_user_can_view_home(): void
    {
        $user = $this->actingAsUser();

        $this->actingAs($user)
             ->get(route('home.index'))
             ->assertOk()
             ->assertViewIs('home')
             ->assertViewHas('applications');
    }

    public function test_home_index_shows_applications_paginated(): void
    {
        $user   = $this->actingAsUser();
        Application::factory()->count(12)->create();

        $response = $this->actingAs($user)->get(route('home.index'));

        $applications = $response->viewData('applications');
        $this->assertEquals(10, $applications->perPage());
        $this->assertEquals(12, $applications->total());
    }

    // ──────────────────────────────────────────────
    // create
    // ──────────────────────────────────────────────

    public function test_authenticated_user_can_view_create_course_form(): void
    {
        $user = $this->actingAsUser();
        Category::factory()->count(3)->create();

        $this->actingAs($user)
             ->get(route('home.course.create'))
             ->assertOk()
             ->assertViewIs('courses.create')
             ->assertViewHas('categories')
             ->assertViewHas('courses');
    }

    // ──────────────────────────────────────────────
    // store (создание курса)
    // ──────────────────────────────────────────────

    public function test_authenticated_user_can_create_course(): void
    {
        $user     = $this->actingAsUser();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('home.course.store'), [
            'title'       => 'Новый курс PHP',
            'duration'    => 40,
            'cost'        => 15000.00,
            'description' => 'Описание нового курса',
            'category_id' => $category->id,
            'image'       => 'images/php.jpg',
        ]);

        $response->assertRedirect(route('home.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('courses', [
            'title'       => 'Новый курс PHP',
            'category_id' => $category->id,
        ]);
    }

    public function test_store_course_validates_required_fields(): void
    {
        $user = $this->actingAsUser();

        $this->actingAs($user)
             ->post(route('home.course.store'), [])
             ->assertSessionHasErrors(['title', 'duration', 'cost', 'description', 'category_id']);
    }

    public function test_store_course_rejects_nonexistent_category(): void
    {
        $user = $this->actingAsUser();

        $this->actingAs($user)
             ->post(route('home.course.store'), [
                 'title'       => 'Тест',
                 'duration'    => 10,
                 'cost'        => 1000,
                 'description' => 'Тест',
                 'category_id' => 9999,
             ])
             ->assertSessionHasErrors(['category_id']);
    }

    public function test_store_course_rejects_negative_duration(): void
    {
        $user     = $this->actingAsUser();
        $category = Category::factory()->create();

        $this->actingAs($user)
             ->post(route('home.course.store'), [
                 'title'       => 'Тест',
                 'duration'    => -1,
                 'cost'        => 1000,
                 'description' => 'Тест',
                 'category_id' => $category->id,
             ])
             ->assertSessionHasErrors(['duration']);
    }

    // ──────────────────────────────────────────────
    // destroyApplication
    // ──────────────────────────────────────────────

    public function test_authenticated_user_can_delete_application(): void
    {
        $user        = $this->actingAsUser();
        $application = Application::factory()->create();

        $response = $this->actingAs($user)
                         ->delete(route('home.application.destroy', $application));

        $response->assertRedirect(route('home.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('applications', ['id' => $application->id]);
    }

    public function test_guest_cannot_delete_application(): void
    {
        $application = Application::factory()->create();

        $this->delete(route('home.application.destroy', $application))
             ->assertRedirect(route('login'));

        $this->assertDatabaseHas('applications', ['id' => $application->id]);
    }
}
