<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Purchase Status Enum
 *
 * Represents all possible states of a purchase order
 */
enum PurchaseStatus: string
{
    case DRAFT = 'draft';
    case APPROVED = 'approved';
    case RECEIVED = 'received';
    case PARTIALLY_RECEIVED = 'partially_received';
    case CANCELLED = 'cancelled';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('Draft'),
            self::APPROVED => __('Approved'),
            self::RECEIVED => __('Received'),
            self::PARTIALLY_RECEIVED => __('Partially Received'),
            self::CANCELLED => __('Cancelled'),
        };
    }

    /**
     * Get color for display.
     */
    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'slate',
            self::APPROVED => 'blue',
            self::RECEIVED => 'green',
            self::PARTIALLY_RECEIVED => 'amber',
            self::CANCELLED => 'red',
        };
    }

    /**
     * Check if status is final.
     */
    public function isFinal(): bool
    {
        return in_array($this, [self::RECEIVED, self::CANCELLED]);
    }

    /**
     * Get allowed next statuses.
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::DRAFT => [self::APPROVED, self::CANCELLED],
            self::APPROVED => [self::RECEIVED, self::PARTIALLY_RECEIVED, self::CANCELLED],
            self::PARTIALLY_RECEIVED => [self::RECEIVED, self::CANCELLED],
            self::RECEIVED => [],
            self::CANCELLED => [],
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
