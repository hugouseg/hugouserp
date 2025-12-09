<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Trait to enable undo functionality for soft-deleted models
 * 
 * This trait extends SoftDeletes to provide an undo mechanism
 * for recently deleted records.
 */
trait HasUndoableDeletes
{
    use SoftDeletes;

    /**
     * The time window (in seconds) during which an undo is possible
     */
    protected int $undoWindow = 30;

    /**
     * Check if this model can be restored (within undo window)
     */
    public function canUndo(): bool
    {
        if (!$this->trashed()) {
            return false;
        }

        $deletedAt = $this->deleted_at;
        $undoDeadline = now()->subSeconds($this->undoWindow);

        return $deletedAt->greaterThan($undoDeadline);
    }

    /**
     * Undo the deletion if within the undo window
     */
    public function undo(): bool
    {
        if (!$this->canUndo()) {
            return false;
        }

        return $this->restore();
    }

    /**
     * Get the undo window in seconds
     */
    public function getUndoWindow(): int
    {
        return $this->undoWindow;
    }

    /**
     * Set the undo window in seconds
     */
    public function setUndoWindow(int $seconds): static
    {
        $this->undoWindow = $seconds;
        return $this;
    }

    /**
     * Scope to get recently deleted items (within undo window)
     */
    public function scopeRecentlyDeleted($query)
    {
        return $query->onlyTrashed()
            ->where('deleted_at', '>=', now()->subSeconds($this->undoWindow));
    }

    /**
     * Boot the trait
     */
    protected static function bootHasUndoableDeletes(): void
    {
        // You can add event listeners here if needed
        // For example, to automatically permanently delete records after undo window
    }
}
