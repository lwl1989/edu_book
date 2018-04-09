<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }


    /**
     * Render an exception into a response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        //接口
        if( strpos($request->path(),'api/') === 0) {
            return $this->_apiRender($e);
        }else{
            return parent::render($request, $e);
        }
    }

    private function _apiRender(Exception $exception) : Response {
        $e = $this->prepareException($exception);

        $status = 400;
        if ($e instanceof HttpResponseException) {
            $status = 419;
        } elseif ($e instanceof AuthenticationException) {
            $status = 401;
        } elseif ($e instanceof ValidationException) {
            $status = 400;
        }
        $code = intval($e->getCode());
        $code = $code > 0 ? $code : 1;
        return response()->json(['code'=>$code, 'response' => $e->getMessage()], $status);
    }


}
