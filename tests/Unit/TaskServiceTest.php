<?php

namespace Tests\Unit;

use App\Services\TaskService;
use App\Models\Task;
use App\Interfaces\TaskRepositoryInterface;
use Tests\TestCase;
use Exception;

// TODO: verify the tasks factory creation
class TaskServiceTest extends TestCase
{
    private $taskRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepositoryMock = $this->mock(TaskRepositoryInterface::class);
    }

    public function testShouldListAllTasks(): void
    {
        // Arrange
        $fakeTasks = Task::factory()->times(10)->new();
        $this->taskRepositoryMock
            ->shouldReceive('getAll')
            ->andReturn($fakeTasks);
        $taskService = new TaskService($this->taskRepositoryMock);

        // Act
        $result = $taskService->getAll();

        // Assert
        $this->assertEquals($fakeTasks, $result);
    }

    public function testShouldThrowErrorWhenModelReturnsErrorOnListAllTasks(): void
    {
        // Arrange
        $this->taskRepositoryMock
            ->shouldReceive('getAll')
            ->andThrow(new Exception('Expected Exception was thrown'));
        $taskService = new TaskService($this->taskRepositoryMock);

        // Act
        $result = $taskService->getAll();

        // Assert
        $this->assertArrayHasKey('error', $result);
    }

    public function testShouldListOneTasks(): void
    {
        // Arrange
        $fakeTask = Task::factory()->new()->make();
        $this->taskRepositoryMock
            ->shouldReceive('getOne')
            ->andReturn($fakeTask);
        $taskService = new TaskService($this->taskRepositoryMock);
        $param = $fakeTask->id;

        // Act
        $result = $taskService->getOne($param);

        // Assert
        $this->assertEquals($fakeTask, $result);
    }

    public function testShouldThrowErrorWhenModelReturnsErrorOnListOneTasks(): void
    {
        // Arrange
        $this->taskRepositoryMock
            ->shouldReceive('getOne')
            ->andThrow(new Exception('Expected Exception was thrown'));
        $taskService = new TaskService($this->taskRepositoryMock);

        // Act
        $result = $taskService->getOne(1);

        // Assert
        $this->assertArrayHasKey('error', $result);
    }

    public function testShouldCreateATask(): void
    {
        // Arrange
        $fakeTask = Task::factory()->new()->make();
        $this->taskRepositoryMock
            ->shouldReceive('create')
            ->andReturn($fakeTask->getAttributes());
        $taskService = new TaskService($this->taskRepositoryMock);
        $expectedResponse = $fakeTask->getAttributes();
        $body = $fakeTask->getAttributes();

        // Act
        $result = $taskService->create($body);

        // Assert
        $this->assertEquals($expectedResponse, $result);
    }

    public function testShouldThrowValidationErrorWhenParamsInvalidOnCreateAPatient(): void
    {
        // Arrange
        $fakeTask = [];
        $this->taskRepositoryMock
            ->shouldReceive('create')
            ->andReturn($fakeTask);
        $taskService = new TaskService($this->taskRepositoryMock);
        $body = $fakeTask;

        // Act
        $result = $taskService->create($body);

        // Assert
        $this->assertArrayHasKey('error', $result);
    }

    public function testShouldThrowErrorWhenModelReturnsErrorOnCreateAPatient(): void
    {
        // Arrange
        $fakeTask = Task::factory()->new()->make();
        $this->taskRepositoryMock
            ->shouldReceive('create')
            ->andThrow(new Exception('Expected Exception was thrown'));
        $taskService = new TaskService($this->taskRepositoryMock);
        $body = $fakeTask;

        // Act
        $result = $taskService->create($body);

        // Assert
        $this->assertArrayHasKey('error', $result);
    }

    public function testShouldUpdateATask(): void
    {
        // Arrange
        $fakeTask = Task::factory()->new()->make();
        $this->taskRepositoryMock
            ->shouldReceive('update')
            ->andReturn($fakeTask->getAttributes());
        $taskService = new TaskService($this->taskRepositoryMock);
        $body = $fakeTask->getAttributes();
        $expectedResponse = $fakeTask->getAttributes();
        $taskId = rand(1, 5);

        // Act
        $result = $taskService->update($taskId, $body);

        // Assert
        $this->assertEquals($expectedResponse, $result);
    }

    public function testShouldThrowValidationErrorWhenParamsInvalidOnUpdateATask(): void
    {
        // Arrange
        $fakeTask = [];
        $this->taskRepositoryMock
            ->shouldReceive('update')
            ->andReturn($fakeTask);
        $taskService = new TaskService($this->taskRepositoryMock);
        $body = $fakeTask;
        $taskId = rand(1, 5);

        // Act
        $result = $taskService->update($taskId, $body);

        // Assert
        $this->assertArrayHasKey('error', $result);
    }

    public function testShouldThrowErrorWhenModelReturnsErrorOnUpdateATask(): void
    {
        // Arrange
        $fakeTask = Task::factory()->new()->make();
        $this->taskRepositoryMock
            ->shouldReceive('update')
            ->andThrow(new Exception('Expected Exception was thrown'));
        $taskService = new TaskService($this->taskRepositoryMock);
        $body = $fakeTask;
        $taskId = rand(1, 5);

        // Act
        $result = $taskService->update($taskId, $body);

        // Assert
        $this->assertArrayHasKey('error', $result);
    }

    public function testShouldDeleteATask(): void
    {
        // Arrange
        $this->taskRepositoryMock
            ->shouldReceive('delete')
            ->andReturn(true);
        $taskService = new TaskService($this->taskRepositoryMock);
        $taskId = rand(1, 5);
        $expectedResponse = 'Task deleted sucefully.';

        // Act
        $result = $taskService->delete($taskId);

        // Assert
        $this->assertEquals($expectedResponse, $result);
    }

    public function testShouldThrowErrorWhenModelReturnsErrorOnDeleteATask(): void
    {
        // Arrange
        $fakeTask = Task::factory()->new()->make();
        $this->taskRepositoryMock
            ->shouldReceive('delete')
            ->andThrow(new Exception('Expected Exception was thrown'));
        $taskService = new TaskService($this->taskRepositoryMock);
        $taskId = rand(1, 5);

        // Act
        $result = $taskService->delete($taskId);

        // Assert
        $this->assertArrayHasKey('error', $result);
    }
}
