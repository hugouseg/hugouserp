<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return $this->renderBusinessException($e, $request);
            }
        });
    }

    /**
     * Render business exceptions with unified response format
     */
    protected function renderBusinessException(Throwable $e, $request)
    {
        $isBusinessException = $e instanceof BusinessException;
        
        $message = $isBusinessException
            ? $e->getMessage()
            : (config('app.debug') ? $e->getMessage() : __('Something went wrong.'));

        $meta = config('app.debug')
            ? [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]
            : [];

        $statusCode = $this->getStatusCode($e);

        return response()->json([
            'success' => false,
            'message' => $message,
            'meta' => $meta,
        ], $statusCode);
    }

    /**
     * Get HTTP status code from exception
     */
    protected function getStatusCode(Throwable $e): int
    {
        if (method_exists($e, 'getStatusCode')) {
            return $e->getStatusCode();
        }

        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return 422;
        }

        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return 401;
        }

        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return 403;
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return $e->getStatusCode();
        }

        return 500;
    }
}
