<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Rental Contract Status Enum
 *
 * Represents all possible states of a rental contract
 */
enum RentalContractStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case TERMINATED = 'terminated';
    case EXPIRED = 'expired';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('Draft'),
            self::ACTIVE => __('Active'),
            self::SUSPENDED => __('Suspended'),
            self::TERMINATED => __('Terminated'),
            self::EXPIRED => __('Expired'),
        };
    }

    /**
     * Get color for display.
     */
    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'slate',
            self::ACTIVE => 'green',
            self::SUSPENDED => 'amber',
            self::TERMINATED => 'red',
            self::EXPIRED => 'gray',
        };
    }

    /**
     * Check if status is final.
     */
    public function isFinal(): bool
    {
        return in_array($this, [self::TERMINATED, self::EXPIRED]);
    }

    /**
     * Get allowed next statuses.
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::DRAFT => [self::ACTIVE],
            self::ACTIVE => [self::SUSPENDED, self::TERMINATED],
            self::SUSPENDED => [self::ACTIVE, self::TERMINATED],
            self::TERMINATED => [],
            self::EXPIRED => [],
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
