<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Обработка ошибок для API
        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->handleApiException($e);
            }
        });
    }

    /**
     * Handle API exceptions
     */
    private function handleApiException(Throwable $e): JsonResponse
    {
        if ($e instanceof ModelNotFoundException) {
            $model = class_basename($e->getModel());
            $message = "{$model} not found";
            
            return response()->json([
                'error' => $message,
                'message' => $message,
                'status' => 404
            ], 404);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'error' => 'Resource not found',
                'message' => 'The requested resource was not found',
                'status' => 404
            ], 404);
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => 'The given data was invalid',
                'errors' => $e->errors(),
                'status' => 422
            ], 422);
        }

        // Общая ошибка сервера
        return response()->json([
            'error' => 'Internal server error',
            'message' => 'Something went wrong',
            'status' => 500
        ], 500);
    }
} 