<?php

namespace App\Exceptions;

use App\Models\Log\SqlErrorLog;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

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
     * @throws Exception
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
            return $this->_webRender($request, $e);
        }
    }

    /**
     * @param Request $request
     * @param Exception $e
     * @return JsonResponse|\Illuminate\Http\Response|Response
     */
    public function _webRender(Request $request, Exception $e)
    {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }



        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        } elseif ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        return $request->expectsJson()
            ? $this->prepareJsonResponse($request, $e)
            : $this->prepareResponse($request, $e);
    }

    /**
     * @param Request $request
     * @param Exception $e
     * @return JsonResponse
     */
    protected function prepareJsonResponse($request, Exception $e)
    {
        $e = $this->prepareException($e);

        $status = 200;
        if ($e instanceof AuthenticationException) {
            $status = 401;
        }

        $headers = $this->isHttpException($e) ? $e->getHeaders() : [];

        $data = [];
        $data['response'] = $e->getMessage();
        $code = intval($e->getCode());
        $code = $code > 0 ? $code : 1;

        if($e instanceof MethodNotAllowedException or $e instanceof NotFoundHttpException) {
            $data = '1000';
        }
        if($e instanceof \PDOException)
        {
            $errorLogId = SqlErrorLog::query()->insertGetId([
                'line'      =>  $e->getLine(),
                'file'      =>  $e->getFile(),
                'error_msg' =>  $e->getMessage()
            ]);
            $code = ErrorConstant::SYSTEM_ERR_PDO;
            $data['response'] = ['error_log_id'=>$errorLogId];
        }
        $data['code'] = $code;
        return new JsonResponse(
            $data, $status, $headers,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * @param Exception $exception
     * @return Response
     */
    private function _apiRender(Exception $exception) : Response {
        $e = $this->prepareException($exception);

        $code = intval($e->getCode());
        $code = $code > 0 ? $code : 1;
        $status = 200;

        //只有認證錯誤返回401
        if ($e instanceof AuthenticationException) {
            $code = ErrorConstant::UN_AUTH_ERROR;
            $status = 401;
        }
        /* 只有認證錯誤單獨返回
        if ($e instanceof HttpResponseException) {
           $status = 419;
        } elseif ($e instanceof AuthenticationException) {
            $code = ErrorConstant::UN_AUTH_ERROR;
            $status = 401;
        } elseif ($e instanceof ValidationException) {
           $status = 400;
        }
        */

        if($e instanceof MethodNotAllowedException or $e instanceof NotFoundHttpException) {
            $code = '1000';
        }
        if($e instanceof \PDOException)
        {
            $errorLogId = SqlErrorLog::query()->insertGetId([
                'line'      =>  $e->getLine(),
                'file'      =>  $e->getFile(),
                'error_msg' =>  $e->getMessage()
            ]);
            $code = ErrorConstant::SYSTEM_ERR_PDO;
            return response()->json(['code'=>strval($code), 'response' => ['error_log_id'=>$errorLogId]], $status);
        }

        return response()->json(['code'=>strval($code), 'response' => $e->getMessage()], $status);
    }


    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {

        // route('login') =>  https => http => It's https://xxx.com/login => http://xxx.com/login  todo:
        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest(env('APP_URL','localhost:8000').'/login');
    }

}
