<?php

namespace App\Exceptions;

use App\Http\Controllers\Api\Traits\ResponseTrait;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
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
     *
     * @throws \Exception
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($request->route()) {
            $prefix = $request->route()->getPrefix();
            if(strpos($prefix, "api") === 0) {
                if ($exception instanceof UnauthorizedHttpException) {
                    // detect previous instance
                    if ($exception->getPrevious() instanceof TokenExpiredException) {
                        return $this->responseErrors($exception->getStatusCode(), 'TOKEN_EXPIRED');
                    } else if ($exception->getPrevious() instanceof TokenInvalidException) {
                        return $this->responseErrors($exception->getStatusCode(), 'TOKEN_INVALID');
                    } else if ($exception->getPrevious() instanceof TokenBlacklistedException) {
                        return $this->responseErrors($exception->getStatusCode(), 'TOKEN_BLACKLISTED');
                    } else {
                        return $this->responseErrors(401, 'UNAUTHORIZED_REQUEST');
                    }
                } elseif ($exception instanceof UnauthorizedException && (strpos($prefix, "api") === 0)) {
                    return $this->responseErrors(403, "Hiện tại bạn không thể thực hiện hành động này.");
                }
            }
        }
        return parent::render($request, $exception);
    }
}
