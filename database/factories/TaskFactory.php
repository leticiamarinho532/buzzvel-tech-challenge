<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

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
        Storage::fake('tasks');

        return [
            'id' => $this->faker->unique()->numberBetween(1, 10),
            'title' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'file' => UploadedFile::fake()->create('exemple-file.txt'),
            'user_id' => 1,
            'completed' => (bool)random_int(0, 1),
            'created_at' => $this->faker->time
        ];
    }
}
