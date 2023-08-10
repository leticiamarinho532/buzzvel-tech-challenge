<?php

namespace App\Services;

use App\Interfaces\TaskRepositoryInterface;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ItemNotFoundException;

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

            if (!$result) {
                throw new ItemNotFoundException('Task not found.');
            }

            return $result;
        } catch (ItemNotFoundException $e) {
            Log::error('Error in list the details of one task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => $e->getMessage(), 'code' => 404];
        } catch (ValidationException $e) {
            Log::error('Error in list the details of one task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => $e->errors(), 'code' => 422];
        } catch (Exception $e) {
            Log::error('Error in list the details of one task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => 'It was no possible to list the details the task', 'code' => 406];
        }
    }

    public function create(array $taskInfos): array|object
    {
        try {
            $data = $this->validateAndPrepareData($taskInfos);

            $result = $this->taskRepository->create($data);

            return $result;
        } catch (ValidationException $e) {
            Log::error('Error in create task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => $e->errors(), 'code' => 422];
        } catch (Exception $e) {
            Log::error('Error in create task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => 'It was no possible to create a task.', 'code' => 406];
        }
    }

    public function update(int $taskId, array $taskInfos): array|object
    {
        try {
            if (!$this->taskRepository->getOne($taskId)) {
                throw new ItemNotFoundException('Task not found.');
            }

            $data = $this->validateAndPrepareData($taskInfos);

            $result = $this->taskRepository->update($taskId, $data);

            return $result;
        } catch (ItemNotFoundException $e) {
            Log::error('Error in update a task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => $e->getMessage(), 'code' => 404];
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
            if (!$this->taskRepository->getOne($taskId)) {
                throw new ItemNotFoundException('Task not found.');
            }

            $this->taskRepository->delete($taskId);

            return 'Tasks deleted successfully.';
        } catch (ItemNotFoundException $e) {
            Log::error('Error in delete a task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => $e->getMessage(), 'code' => 404];
        } catch (ValidationException $e) {
            Log::error('Error in delete a task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => $e->errors(), 'code' => 422];
        } catch (Exception $e) {
            Log::error('Error in delete a task: ' . $e->getMessage(), ['feature' => 'task']);

            return ['error' => true, 'message' => 'It was no possible to delete the task.', 'code' => 406];
        }
    }

    private function validateAndPrepareData(array $taskInfos): array
    {
        $this->validateParams($taskInfos);

        $fileName = uniqid() . '.' . $taskInfos['file']->getClientOriginalExtension();
        $storedFile = Storage::disk('public')->put($fileName, file_get_contents($taskInfos['file']));

        if (!$storedFile) {
            throw new Exception('Error in store file in local.');
        }

        return $this->formatTaskData($taskInfos, $fileName);
    }

    private function validateParams(array $params): null|Exception
    {
        $validator = Validator::make(
            $params,
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'file' => 'required|file|max:255',
                'completed' => 'required|boolean',
                'user_id' => 'required|integer',
                'completed_at' => 'date',
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator, $validator->messages());
        }

        return null;
    }

    private function formatTaskData(array $data, string $fileName): array
    {
        $data['file'] = Storage::url($fileName);

        return $data;
    }

}