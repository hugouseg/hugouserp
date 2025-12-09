<?php

declare(strict_types=1);

namespace App\Exceptions;

class NoBranchSelectedException extends BusinessException
{
    public function __construct(
        string $message = 'No branch selected. Please select a branch to continue.',
        int $code = 422
    ) {
        parent::__construct(__($message), $code);
    }
}
