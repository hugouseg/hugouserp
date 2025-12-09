<?php

declare(strict_types=1);

namespace App\Exceptions;

class InsufficientStockException extends BusinessException
{
    public function __construct(
        string $productName,
        float $available,
        float $requested,
        int $code = 422
    ) {
        $message = __('Insufficient stock for :product. Available: :available, Requested: :requested', [
            'product' => $productName,
            'available' => $available,
            'requested' => $requested,
        ]);

        parent::__construct($message, $code);
    }
}
