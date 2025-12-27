<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Todo\Infrastructure\Persistence\Sqlite\SqliteTaskRepository;
use App\Todo\Application\CreateTask\CreateTaskHandler;
use App\Todo\Infrastructure\Delivery\Http\CreateTaskController;
use App\Todo\Application\ListTasks\ListTasksQuery;
use App\Todo\Application\ListTasks\ListTasksHandler;

// 1. Set up the SQLite connection
$pdo = new PDO('sqlite:' . __DIR__ . '/../data/task_manager.sqlite');
$repository = new SqliteTaskRepository($pdo);
$createHandler = new CreateTaskHandler($repository);
$listHandler = new ListTasksHandler($repository);

// 2. Set up the controller
$controller = new CreateTaskController($createHandler);

// 3. Handle the request
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

if ($method === 'POST' && str_contains($path, '/tasks')) {
    echo $controller();
} 
elseif ($method === 'GET' && str_contains($path, '/tasks')) {
    $query = new ListTasksQuery();
    $tasks = ($listHandler)($query);

    $taskArray = array_map(function($task) {
        return [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'priority' => $task->getPriority()->value,
            'is_completed' => $task->isCompleted(),
        ];
    }, $tasks);
    
    header('Content-Type: application/json');
    echo json_encode($taskArray);
}
else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}

