<?php

namespace App\Todo\Domain\Model;

enum Priority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case URGENT = 'urgent';

    public function isUrgent(): bool
    {
        return $this === self::URGENT;
    }
}