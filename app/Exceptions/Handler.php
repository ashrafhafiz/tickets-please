<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponses;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ApiResponses;
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

    protected $handlers = [
        ValidationException::class => 'handleValidationException',
        ModelNotFoundException::class => 'handleModelNotFoundException',
        AuthenticationException::class => 'handleAuthenticationException',
        AuthorizationException::class => 'handleAuthorizationException',
    ];

    private function handleValidationException(ValidationException $exception)
    {
        foreach ($exception->errors() as $key => $value) {
            foreach ($value as $message) {
                $errors[] = [
                    'status' => 422,
                    'message' => $message,
                    'source' => $key
                ];
            }
        }
        return $errors;
    }

    private function handleModelNotFoundException(ModelNotFoundException $exception)
    {
        $errors[] = [
            // 'type' => 'ModelNotFoundException',
            // 'status' => 404,
            // 'message' => $exception->getMessage(),
            // 'source' => 'Line: ' . $exception->getLine() . ': ' . $exception->getFile(),
            'status' => 404,
            'message' => 'The resource can not be found.',
            'source' => $exception->getModel(),
        ];
        return $errors;
    }

    private function handleAuthenticationException(AuthenticationException $exception)
    {
        $errors[] = [
            'status' => 401,
            'message' => 'You are not authenticated.',
            'source' => '',
        ];
        return $errors;
    }
    private function handleAuthorizationException(AuthorizationException $exception)
    {
        $errors[] = [
            'status' => 401,
            'message' => 'You are not authorized to perform this action.',
            'source' => '',
        ];
        return $errors;
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $exceptionClassName = get_class($exception);
        $index = strrpos($exceptionClassName, '\\');
        $type = substr($exceptionClassName, $index + 1);

        // if ($exceptionClassName == ValidationException::class) {
        //     foreach ($exception->errors() as $key => $value) {
        //         foreach ($value as $message) {
        //             $errors[] = [
        //                 'status' => 422,
        //                 'message' => $message,
        //                 'source' => $key
        //             ];
        //         }
        //     }
        //     return $this->error($errors);
        // }

        if (array_key_exists($exceptionClassName, $this->handlers)) {
            $method = $this->handlers[$exceptionClassName];
            return $this->error($this->$method($exception));
        }

        return $this->error([
            [
                'type' => $type,
                'status' => 0,
                'message' => $exception->getMessage(),
                'source' => 'Line: ' . $exception->getLine() . ': ' . $exception->getFile(),
            ]
        ]);
    }
}
