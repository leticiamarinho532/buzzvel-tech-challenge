<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class TaskController extends Controller
{
    public function __construct(
        private TaskRepository $taskRepository,
    )
    {
    }

    public function index(): JsonResponse
    {
        $input = request(['userId']);

        $task = new TaskService($this->taskRepository);

        $result = $task->getAll($input['userId']);

        if (is_array($result) && in_array('error', $result)) {
            return response()->json([
                'message' => $result['message']
            ], $result['code']);
        }

        return response()->json($result, 200);
    }

    public function show(int $doctorId): JsonResponse
    {
        $task = new TaskService($this->taskRepository);

        $result = $task->getOne($doctorId);

        if (is_array($result) && in_array('error', $result)) {
            return response()->json([
                'message' => $result['message']
            ], $result['code']);
        }

        return response()->json($result, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $task = new TaskService($this->taskRepository);

        $result = $task->create($input);

        if (is_array($result) && in_array('error', $result)) {
            return response()->json([
                'message' => $result['message']
            ], $result['code']);
        }

        return response()->json($result, 201);
    }

    public function update(int $taskId, Request $request): JsonResponse
    {
        $input = $request->all();

        dd($input);

        $task = new TaskService($this->taskRepository);

        $result = $task->update($taskId, $input);

        if (is_array($result) && in_array('error', $result)) {
            return response()->json([
                'message' => $result['message']
            ], $result['code']);
        }

        return response()->json($result, 200);
    }

    public function destroy(int $taskId): JsonResponse
    {
        $task = new TaskService($this->taskRepository);

        $result = $task->delete($taskId);

        if (is_array($result) && in_array('error', $result)) {
            return response()->json([
                'message' => $result['message']
            ], $result['code']);
        }

        return response()->json($result, 200);
    }
}
