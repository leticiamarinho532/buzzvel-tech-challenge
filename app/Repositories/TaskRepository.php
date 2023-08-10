<?php

namespace App\Repositories;

use App\Models\Task;
use App\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll($userId): mixed
    {
        return Task::where('user_id', '=', $userId)->get();
    }

    public function create(int $userId, array|object $taskInfos): mixed
    {
        $taskInfos['user_id'] = $userId;

        return Task::firstOrCreate($taskInfos);
    }  

    public function getOne(int $taksId): mixed
    {
        return Task::find($taksId);
    }

    public function update(int $userId, int $taskId, array|object $taskInfos): mixed
    {
        $taskInfos['user_id'] = $userId;

        Task::where('id', '=', $taskId)
            ->update($taskInfos);

        return Task::find($taskId);
    }

    public function delete(int $taskId): mixed
    {
        $task = Task::find($taskId);
 
        return $task->delete();
    }
}