<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Sale Status Enum
 *
 * Represents all possible states of a sale/invoice
 */
enum SaleStatus: string
{
    case DRAFT = 'draft';
    case CONFIRMED = 'confirmed';
    case PAID = 'paid';
    case PARTIALLY_PAID = 'partially_paid';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('Draft'),
            self::CONFIRMED => __('Confirmed'),
            self::PAID => __('Paid'),
            self::PARTIALLY_PAID => __('Partially Paid'),
            self::CANCELLED => __('Cancelled'),
            self::REFUNDED => __('Refunded'),
        };
    }

    /**
     * Get color for display.
     */
    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'slate',
            self::CONFIRMED => 'blue',
            self::PAID => 'green',
            self::PARTIALLY_PAID => 'amber',
            self::CANCELLED => 'red',
            self::REFUNDED => 'purple',
        };
    }

    /**
     * Check if status is final (cannot be changed).
     */
    public function isFinal(): bool
    {
        return in_array($this, [self::PAID, self::CANCELLED, self::REFUNDED]);
    }

    /**
     * Get allowed next statuses.
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::DRAFT => [self::CONFIRMED, self::CANCELLED],
            self::CONFIRMED => [self::PAID, self::PARTIALLY_PAID, self::CANCELLED],
            self::PARTIALLY_PAID => [self::PAID, self::CANCELLED],
            self::PAID => [self::REFUNDED],
            self::CANCELLED => [],
            self::REFUNDED => [],
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
