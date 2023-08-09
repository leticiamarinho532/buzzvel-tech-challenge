<?php

namespace App\Services;

use App\Interfaces\TaskRepositoryInterface;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
    ) {
    }

    public function getAll(): array|object
    {
        try {
            $result = $this->taskRepository->getAll();

            return $result;
        } catch (ValidationException $e) {
            Log::error('Error in list all tasks: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => $e->errors(), 'code' => 422];
        } catch (Exception $e) {
            Log::error('Error in list all tasks: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => 'It was no possible to list all tasks.', 'code' => 406];
        }
    }

    public function getOne(int $taksId): array|object
    {
        try {
            $result = $this->taskRepository->getOne($taksId);

            return $result;
        } catch (ValidationException $e) {
            Log::error('Error in list the details of one task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => $e->errors(), 'code' => 422];
        } catch (Exception $e) {
            Log::error('Error in list the details of one task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => 'It was no possible to list the details the task', 'code' => 406];
        }
    }

    public function create(array|object $taskInfos): array|object
    {
        try {
            $this->validateParams($taskInfos);

            $originalFileName = $taskInfos->file->getClientOriginalName();
            $storedFile = Storage::disk('local')->put($originalFileName, file_get_contents($taskInfos->file));

            if (!$storedFile) {
                throw new Exception('Error in store file in local.');
            }

            $result = $this->taskRepository->create($taskInfos);

            return $result;
        } catch (ValidationException $e) {
            Log::error('Error in create task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => $e->errors(), 'code' => 422];
        } catch (Exception $e) {
            Log::error('Error in create task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => 'It was no possible to create a task.', 'code' => 406];
        }
    }

    public function update(int $taskId, array|object $taskInfos): array|object
    {
        try {
            $this->validateParams($taskInfos);

            $originalFileName = $taskInfos->file->getClientOriginalName();
            $storedFile = Storage::disk('local')->put($originalFileName, file_get_contents($taskInfos->file));

            if (!$storedFile) {
                throw new Exception('Error in store file in local.');
            }

            $result = $this->taskRepository->update($taskId, $taskInfos);

            return $result;
        } catch (ValidationException $e) {
            Log::error('Error in update a task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => $e->errors(), 'code' => 422];
        } catch (Exception $e) {
            Log::error('Error in update a task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => 'It was no possible to update the task.', 'code' => 406];
        }
    }

    public function delete(int $taskId): bool|array
    {
        try {
            $result = $this->taskRepository->delete($taskId);

            return $result;
        } catch (ValidationException $e) {
            Log::error('Error in delete a task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => $e->errors(), 'code' => 422];
        } catch (Exception $e) {
            Log::error('Error in delete a task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => 'It was no possible to delete the task.', 'code' => 406];
        }
    }

    private function validateParams(array|object $params): null|Exception
    {
        $validator = Validator::make(
            $params,
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'file' => 'required|string|max:255',
                'completed' => 'required|boolean',
                'user_id' => 'required|integer',
                'completed_at' => 'required|date',
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator, $validator->messages());
        }

        return null;
    }
}