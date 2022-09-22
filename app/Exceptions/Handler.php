<?php

namespace App\Exceptions;

use App\Http\Services\Tabby\Exceptions\InvalidPaymentId;
use App\Http\Services\Tamara\Exceptions\NoAvailablePaymentOption;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param \Throwable $exception
     * @return Response|JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {

            if (config('app.debug')) {
                $response['message'] = $exception->getMessage();
                $response['exception'] = get_class($exception);
                $response['file'] = $exception->getFile();
                $response['line'] = $exception->getLine();
                $response['trace'] = $exception->getTrace();
            }

            if ($exception instanceof ValidationException) {
                $response['message'] = 'The given data was invalid.';
                $response['errors'] = $exception->validator->errors()->all();
                return response()->json($response, 422);
            }

            if (
                $exception instanceof ConnectionException ||
                $exception instanceof RequestException ||
                $exception instanceof InvalidPaymentId ||
                $exception instanceof NoAvailablePaymentOption
            ) {
                $response['message'] = $exception->getMessage();
                return response()->json($response, 400);
            }
        }

        return parent::render($request, $exception);
    }

}
