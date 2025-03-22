<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $e) {});
    }



    public function render($request, Throwable $exception)
    {
        // Kiểm tra nếu lỗi là ValidationException
        if ($exception instanceof ValidationException) {
            // Tùy chỉnh phản hồi lỗi
            return response()->json([
                'success' => false,
                'errors' => $exception->errors(),
            ], 422);
        }

        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        if ($exception instanceof AuthorizationException || $exception instanceof UnauthorizedHttpException) {
            // Trả về JSON response với mã lỗi 403 (Forbidden)
            return response()->json([
                'message' => 'You do not have permission to perform this action.'
            ], 403);
        }

        // Xử lý các lỗi khác bình thường
        return parent::render($request, $exception);
    }

    public function report(Throwable $exception)
    {
        Log::error('>>Exception occurred<<', [
            'message' => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
        ]);

        parent::report($exception);
    }
}
