<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'full_name'        => $this->faker->name(),
            'email'            => $this->faker->safeEmail(),
            'course_id'        => Course::factory(),
            'application_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'status'           => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function approved(): static
    {
        return $this->state(['status' => 'approved']);
    }

    public function rejected(): static
    {
        return $this->state(['status' => 'rejected']);
    }
}
