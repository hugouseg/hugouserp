<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class GoodsReceivedNote extends BaseModel
{
    protected ?string $moduleKey = 'purchases';

    protected $table = 'goods_received_notes';

    protected $fillable = [
        'code', 'branch_id', 'warehouse_id', 'purchase_id', 'supplier_id',
        'status', 'received_date', 'delivery_note_no', 'vehicle_no',
        'received_by', 'inspected_by', 'inspected_at',
        'inspection_status', 'inspection_notes', 'notes',
        'extra_attributes', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'received_date' => 'date',
        'inspected_at' => 'datetime',
        'extra_attributes' => 'array',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function ($model) {
            if (!$model->code) {
                $model->code = 'GRN-' . date('Ymd') . '-' . str_pad((string) (static::whereDate('created_at', today())->count() + 1), 5, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function inspectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(GRNItem::class, 'grn_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopePendingInspection($query)
    {
        return $query->where('status', 'pending_inspection');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Business Logic
    public function approve(int $approvedBy): void
    {
        $this->update([
            'status' => 'approved',
            'inspected_by' => $approvedBy,
            'inspected_at' => now(),
            'inspection_status' => 'passed',
        ]);
    }

    public function reject(int $rejectedBy, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'inspected_by' => $rejectedBy,
            'inspected_at' => now(),
            'inspection_status' => 'failed',
            'inspection_notes' => $reason,
        ]);
    }

    public function canBeApproved(): bool
    {
        return in_array($this->status, ['draft', 'pending_inspection']);
    }

    public function getTotalQuantityReceived(): float
    {
        return $this->items->sum('qty_received');
    }

    public function getTotalQuantityAccepted(): float
    {
        return $this->items->sum('qty_accepted');
    }

    public function getTotalQuantityRejected(): float
    {
        return $this->items->sum('qty_rejected');
    }

    public function hasDiscrepancies(): bool
    {
        return $this->items->contains(function ($item) {
            return $item->qty_received != $item->qty_ordered || $item->qty_rejected > 0;
        });
    }
}
