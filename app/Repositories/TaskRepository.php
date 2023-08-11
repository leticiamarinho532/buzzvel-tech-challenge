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

    public function create(int $userId, array $taskInfos): mixed
    {
        $taskInfos['user_id'] = $userId;

        // return Task::firstOrCreate($taskInfos);
        return Task::firstOrCreate([
            'title' => $taskInfos['title'], 
            'description' => $taskInfos['description'], 
            'file' => $taskInfos['file'], 
            'completed' => $taskInfos['completed'], 
            'user_id' => $userId, 
            isset($taskInfos['completed_at']) ? ['completed_at' => $taskInfos['completed_at']] : ''
        ]);
    }  

    public function getOne(int $taksId): mixed
    {
        return Task::find($taksId);
    }

    public function update(int $userId, int $taskId, array $taskInfos): mixed
    {
        $taskInfos['user_id'] = $userId;

        $data = [
            'title' => $taskInfos['title'], 
            'description' => $taskInfos['description'], 
            'file' => $taskInfos['file'], 
            'completed' => $taskInfos['completed'], 
            'user_id' => $userId,
        ];

        if (isset($taskInfos['completed_at'])) {
            $data['completed_at'] = $taskInfos['completed_at'];
        }

        Task::where('id', '=', $taskId)
            // ->update($taskInfos);
            ->update($data);

        return Task::find($taskId);
    }

    public function delete(int $taskId): mixed
    {
        $task = Task::find($taskId);
 
        return $task->delete();
    }
}