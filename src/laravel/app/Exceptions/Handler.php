<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Http\Controllers\Resources\ResponsePackage;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            if (get_class($exception) == 'Illuminate\Auth\Access\AuthorizationException') {
                return ResponsePackage::error($exception->getMessage(), [], 401)->response();
            }
            if (get_class($exception) == 'Illuminate\Validation\ValidationException') {
                return ResponsePackage::error($exception->getMessage(), $exception->validator->errors(), 422)->response();
            }
            return ResponsePackage::error($exception->getMessage(), $exception->getTrace())->response();
        }
        else {
            return parent::render($request, $exception);
        }
    }
}
