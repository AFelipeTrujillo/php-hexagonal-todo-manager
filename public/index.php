<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Todo\Infrastructure\Persistence\Sqlite\SqliteTaskRepository;
use App\Todo\Application\CreateTask\CreateTaskHandler;
use App\Todo\Infrastructure\Delivery\Http\CreateTaskController;

// 1. Set up the SQLite connection
$pdo = new PDO('sqlite:' . __DIR__ . '/../data/task_manager.sqlite');
$repository = new SqliteTaskRepository($pdo);
$handler = new CreateTaskHandler($repository);

// 2. Set up the controller
$controller = new CreateTaskController($handler);

// 3. Handle the request
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

if ($method === 'POST' && str_contains($path, '/tasks')) {
    echo $controller();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}

