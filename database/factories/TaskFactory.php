<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1, 10),
            'title' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'file' => 'storage/app/example.txt',
            'user_id' => 1,
            'completed' => (bool)random_int(0, 1),
            'completed_at' => (($this->faker->unique()->numberBetween(1, 10) % 2) ? $this->faker->date : null),
            'created_at' => $this->faker->time
        ];
    }
}
