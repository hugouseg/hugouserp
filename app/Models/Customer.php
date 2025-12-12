<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends BaseModel
{
    protected ?string $moduleKey = 'customers';

    protected $table = 'customers';

    protected $fillable = [
        'uuid', 'code', 'name', 'email', 'phone', 'tax_number',
        'billing_address', 'shipping_address', 'price_group_id',
        'address', 'city', 'country', 'company', 'external_id',
        'status', 'notes', 'loyalty_points', 'customer_tier', 'tier_updated_at',
        'extra_attributes', 'branch_id', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'extra_attributes' => 'array',
        'loyalty_points' => 'integer',
        'tier_updated_at' => 'datetime',
        'tax_number' => 'encrypted',
        'phone' => 'encrypted',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function priceGroup(): BelongsTo
    {
        return $this->belongsTo(PriceGroup::class, 'price_group_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function vehicleContracts(): HasMany
    {
        return $this->hasMany(VehicleContract::class);
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }
}
