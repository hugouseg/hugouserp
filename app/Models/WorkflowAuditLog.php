<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowAuditLog extends Model
{
    protected $fillable = [
        'workflow_instance_id',
        'user_id',
        'action',
        'from_stage',
        'to_stage',
        'comments',
        'metadata',
        'performed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'performed_at' => 'datetime',
    ];

    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create audit log entry
     */
    public static function log(
        int $workflowInstanceId,
        ?int $userId,
        string $action,
        ?string $fromStage = null,
        ?string $toStage = null,
        ?string $comments = null,
        ?array $metadata = null
    ): self {
        return static::create([
            'workflow_instance_id' => $workflowInstanceId,
            'user_id' => $userId,
            'action' => $action,
            'from_stage' => $fromStage,
            'to_stage' => $toStage,
            'comments' => $comments,
            'metadata' => $metadata,
            'performed_at' => now(),
        ]);
    }
}
