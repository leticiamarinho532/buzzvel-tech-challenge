<?php

namespace Tests\Feature;

use Database\Seeders\TaskSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Task;

use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(
            [
                TaskSeeder::class
            ]
        );

        // $this->user = User::factory()->create();
        // $this->userToken = JWTAuth::fromUser($this->user);
    }

    public function testShouldShowAllTasks(): void
    {
        // Act
        $response = $this->get('api/tasks');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([[
                    'id',
                    'title',
                    'description',
                    'file',
                    'completed',
                    'user_id',
                    'completed_at',
                    'created_at',
                    'updated_at',
                    'deleted_at'
            ]]);
    }

    public function testShouldShowOneTask(): void
    {
        // Act
        $response = $this->get('api/tasks/1');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                    'id',
                    'title',
                    'description',
                    'file',
                    'completed',
                    'user_id',
                    'completed_at',
                    'created_at',
                    'updated_at',
                    'deleted_at'
            ]);
    }

    public function testShouldCreateATask(): void
    {
        // Arrange
        $fakeTask = Task::factory()->new()->make();
        $body = $fakeTask->toArray();

        // Act
        $response = $this->post('api/tasks', $body);

        // Assert
        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'nome',
                'cpf',
                'celular',
                'created_at',
                'updated_at',
        ]);
    }

    public function testShouldUpdateATask(): void
    {
        // Arrange
        $fakeTask = Task::factory()->new()->make();
        $body = $fakeTask->toArray();

        // Act
        $response = $this->put('api/tasks/1', $body);

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'nome',
                'cpf',
                'celular',
                'created_at',
                'updated_at',
                'deleted_at'
        ]);
    }

    public function testShouldDeleteATask(): void
    {
        // Act
        $response = $this->delete('api/tasks/2');

        // Assert
        $response->assertStatus(204);
    }
}
