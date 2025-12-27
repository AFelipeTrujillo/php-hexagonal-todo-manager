<?php

namespace App\Todo\Domain\Repository;

use App\Todo\Domain\Model\Task;

interface TaskRepository
{
    public function save(Task $task): void;

    public function countUrgentTasks(): int;

    public function findAll(): array;
}