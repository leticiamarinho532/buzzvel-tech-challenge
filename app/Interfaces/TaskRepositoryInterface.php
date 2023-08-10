<?php

namespace App\Interfaces;

interface TaskRepositoryInterface
{
    public function getAll(int $userId): mixed;

    public function create(int $userId, array $taskInfos): mixed;

    public function getOne(int $taksId): mixed;

    public function update(int $userId, int $taskId, array $taskInfos): mixed;

    public function delete(int $taskId): mixed;
}