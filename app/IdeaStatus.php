<?php

namespace App;

enum IdeaStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in-progress';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
        };
    }

    /**
     * Accept valid backing values and common mistakes (case name, underscore).
     */
    public static function fromStored(string $value): self
    {
        return match ($value) {
            'pending', 'Pending' => self::Pending,
            'in-progress', 'in_progress', 'InProgress' => self::InProgress,
            'completed', 'Completed' => self::Completed,
            default => self::tryFrom($value)
                ?? throw new \ValueError("\"{$value}\" is not a valid backing value for enum ".self::class),
        };
    }
}
