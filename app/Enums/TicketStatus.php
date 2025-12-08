<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Ticket Status Enum
 *
 * Represents all possible states of a helpdesk ticket
 */
enum TicketStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case ON_HOLD = 'on_hold';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::OPEN => __('Open'),
            self::IN_PROGRESS => __('In Progress'),
            self::ON_HOLD => __('On Hold'),
            self::RESOLVED => __('Resolved'),
            self::CLOSED => __('Closed'),
        };
    }

    /**
     * Get color for display.
     */
    public function color(): string
    {
        return match ($this) {
            self::OPEN => 'red',
            self::IN_PROGRESS => 'blue',
            self::ON_HOLD => 'amber',
            self::RESOLVED => 'green',
            self::CLOSED => 'slate',
        };
    }

    /**
     * Check if status is final.
     */
    public function isFinal(): bool
    {
        return $this === self::CLOSED;
    }

    /**
     * Get allowed next statuses.
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::OPEN => [self::IN_PROGRESS, self::ON_HOLD, self::CLOSED],
            self::IN_PROGRESS => [self::ON_HOLD, self::RESOLVED, self::CLOSED],
            self::ON_HOLD => [self::IN_PROGRESS, self::CLOSED],
            self::RESOLVED => [self::CLOSED, self::IN_PROGRESS], // Can reopen if needed
            self::CLOSED => [],
        };
    }

    /**
     * Check if transition is allowed.
     */
    public function canTransitionTo(self $newStatus): bool
    {
        return in_array($newStatus, $this->allowedTransitions());
    }
}
