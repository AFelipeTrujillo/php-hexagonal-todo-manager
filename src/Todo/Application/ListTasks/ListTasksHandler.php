<?php

namespace App\Todo\Application\ListTasks;

use App\Todo\Domain\Repository\TaskRepository;

class ListTasksHandler
{
    public function __construct(
        private TaskRepository $repository
    ){}

    public function __invoke(ListTasksQuery $query): array
    {
        return $this->repository->findAll();
    }
}