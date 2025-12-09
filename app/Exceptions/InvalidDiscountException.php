<?php

declare(strict_types=1);

namespace App\Exceptions;

class InvalidDiscountException extends BusinessException
{
    public function __construct(
        float $discount,
        float $maxAllowed,
        string $type = 'percent',
        int $code = 422
    ) {
        $message = __('Discount of :discount:unit exceeds maximum allowed :max:unit', [
            'discount' => $discount,
            'max' => $maxAllowed,
            'unit' => $type === 'percent' ? '%' : ' EGP',
        ]);

        parent::__construct($message, $code);
    }
}
