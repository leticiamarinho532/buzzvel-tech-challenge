<?php

namespace App\Interfaces;

interface TaskRepositoryInterface
{
    public function getAll(): mixed;

    public function create(array|object $taskInfos): mixed;

    public function getOne(int $taksId): mixed;

    public function update(int $taskId, array|object $taskInfos): mixed;

    public function delete(int $taskId): mixed;
}