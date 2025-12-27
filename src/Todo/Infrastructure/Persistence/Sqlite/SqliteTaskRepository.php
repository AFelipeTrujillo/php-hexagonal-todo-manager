<?php

namespace App\Todo\Infrastructure\Persistence\Sqlite;

use App\Todo\Domain\Model\Task;
use App\Todo\Domain\Model\Priority;
use App\Todo\Domain\Repository\TaskRepository;
use PDO;

class SqliteTaskRepository implements TaskRepository
{
    public function __construct(private PDO $connection)
    {}

    public function save(Task $task) : void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO tasks (title, description, priority, is_completed) VALUES (:title, :description, :priority, :is_completed)'
        );

        $statement->execute([
            ':title' => $task->getTitle(),
            ':description' => $task->getDescription(),
            ':priority' => $task->getPriority()->value,
            ':is_completed' => $task->isCompleted() ? 1 : 0,
        ]);
    }

    public function countUrgentTasks() : int
    {
        $statement = $this->connection->prepare(
            "SELECT COUNT(*) FROM tasks WHERE priority = 'urgent' AND is_completed = 0"
        );

        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function findAll() : array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM tasks'
        );

        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $tasks = [];
        foreach ($rows as $row) {
            $tasks[] = new Task(
                (int) $row['id'],
                $row['title'],
                $row['description'],
                Priority::from($row['priority']),
                (bool) $row['is_completed']
            );
        }

        return $tasks;
    }

    public function createTableIfNotExists(): void
    {
        $this->connection->exec(
            'CREATE TABLE IF NOT EXISTS tasks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT NOT NULL,
                priority TEXT NOT NULL,
                is_completed INTEGER NOT NULL CHECK (is_completed IN (0, 1))
            )'
        );
    }
    
}