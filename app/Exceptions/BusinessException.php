<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

/**
 * Base exception for all business logic errors
 */
class BusinessException extends Exception
{
    public function __construct(
        string $message = 'A business logic error occurred',
        int $code = 422,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the HTTP status code for this exception
     */
    public function getStatusCode(): int
    {
        return $this->code >= 400 && $this->code < 600 ? $this->code : 422;
    }

    /**
     * Determine if this exception should be reported
     */
    public function shouldReport(): bool
    {
        return false;
    }
}
