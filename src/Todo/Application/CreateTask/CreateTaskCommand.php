<?php

namespace App\Todo\Application\CreateTask;

readonly class CreateTaskCommand
{
    public function __construct(
        public string $title,
        public string $description,
        public string $priority
    ) {}
}