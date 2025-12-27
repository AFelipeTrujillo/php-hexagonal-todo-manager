<?php

namespace App\Todo\Domain\Exception;

use DomainException;

class PriorityLimitReachedException extends DomainException
{
    public static function forUrgentTasks(): self
    {
        return new self('The limit for urgent priority tasks has been reached.');
    }
}