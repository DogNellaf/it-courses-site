<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_belongs_to_course(): void
    {
        $course      = Course::factory()->create();
        $application = Application::factory()->create(['course_id' => $course->id]);

        $this->assertInstanceOf(Course::class, $application->course);
        $this->assertEquals($course->id, $application->course->id);
    }

    public function test_application_date_is_cast_to_datetime(): void
    {
        $application = Application::factory()->create([
            'application_date' => '2024-01-15 10:30:00',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $application->application_date);
    }

    public function test_application_has_correct_fillable_fields(): void
    {
        $course = Course::factory()->create();

        $application = Application::create([
            'full_name'        => 'Петров Пётр',
            'email'            => 'petr@example.com',
            'course_id'        => $course->id,
            'application_date' => now(),
            'status'           => 'pending',
        ]);

        $this->assertDatabaseHas('applications', [
            'full_name' => 'Петров Пётр',
            'email'     => 'petr@example.com',
        ]);
    }

    public function test_application_default_status_is_pending(): void
    {
        $application = Application::factory()->pending()->create();

        $this->assertEquals('pending', $application->status);
    }

    public function test_application_factory_states(): void
    {
        $approved = Application::factory()->approved()->create();
        $rejected = Application::factory()->rejected()->create();

        $this->assertEquals('approved', $approved->status);
        $this->assertEquals('rejected', $rejected->status);
    }
}
