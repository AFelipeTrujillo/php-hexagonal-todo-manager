<?php

namespace App\Todo\Application\CreateTask;

use App\Todo\Domain\Model\Task;
use App\Todo\Domain\Model\Priority;
use App\Todo\Domain\Repository\TaskRepository;
use App\Todo\Domain\Exception\PriorityLimitReachedException;

class CreateTaskHandler
{
    public function __construct(
        private TaskRepository $repository
    ){}

    public function __invoke(CreateTaskCommand $command): void
    {
        // 1. Validate priority limit
        $priority = Priority::from($command->priority);
        
        // 2. Check urgent task limit
        if ($priority->isUrgent()) {
            $urgentTaskCount = $this->repository->countUrgentTasks();
            if ($urgentTaskCount >= 3) {
                throw PriorityLimitReachedException::forUrgentTasks();
            }
        }

        // 3. Create task
        $task = Task::create(
            $command->title,
            $command->description,
            $priority
        );

        // 4. Save task
        $this->repository->save($task);

    }
}
