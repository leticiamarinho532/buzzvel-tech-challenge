<?php

namespace App\Repositories;

use App\Models\Task;
use App\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll(): mixed
    {
        return Task::all();
    }

    public function create(array|object $taskInfos): mixed
    {
        return Task::firstOrCreate($taskInfos);
    }  

    public function getOne(int $taksId): mixed
    {
        return Task::find($taksId);
    }

    // TODO: verify if add existence in repository or in service
    public function update(int $taskId, array|object $taskInfos): mixed
    {
        Task::where('id', '=', $taskId)
            ->update($taskInfos);

        return Task::find($taskId);
    }

    // TODO: verify if add existence in repository or in service
    public function delete(int $taskId): mixed
    {
        $task = Task::find($taskId);
 
        return $task->delete();
    }
}