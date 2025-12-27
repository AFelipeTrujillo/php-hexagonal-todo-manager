<?php

namespace App\Todo\Domain\Model;

use App\Todo\Domain\Model\Priority;
use InvalidArgumentException;

class Task
{
    private function __construct(
        private ?int $id,
        private string $title,
        private string $description,
        private Priority $priority,
        private bool $isCompleted = false
    ) {}
    
    public static function create(string $title, string $description, Priority $priority): self
    {
        if (empty($title)) {
            throw new InvalidArgumentException('Title cannot be empty.');
        }

        return new self(null, $title, $description, $priority);
    }

    //Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPriority(): Priority
    {
        return $this->priority;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    // Business Logic
    public function isUrgent(): bool
    {
        return $this->priority->isUrgent();
    }
    
}
