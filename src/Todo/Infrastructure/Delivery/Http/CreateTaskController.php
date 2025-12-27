<?php

namespace App\Todo\Infrastructure\Delivery\Http;

use App\Todo\Application\CreateTask\CreateTaskCommand;
use App\Todo\Application\CreateTask\CreateTaskHandler;
use App\Todo\Domain\Exception\PriorityLimitReachedException;
use Exception;

class CreateTaskController
{
    public function __construct(
        private CreateTaskHandler $createTaskHandler
    ) {}

    public function __invoke()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if(!$input || !isset($input['title'], $input['description'], $input['priority'])) {
            return $this->jsonResponse(['error' => 'Invalid input'], 400);
        }

        try {

            $command = new CreateTaskCommand(
                $input['title'],
                $input['description'],
                $input['priority'] ?? 'low'
            );

            ($this->createTaskHandler)($command);

            return $this->jsonResponse(['message' => 'Task created successfully'], 201);

        } catch (PriorityLimitReachedException $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 422);
        } catch (Exception $e) {
            return $this->jsonResponse(['error' => 'An unexpected error occurred'], 500);
        }


    }
    

    private function jsonResponse(array $data, int $statusCode = 200): string
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}