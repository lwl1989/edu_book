<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class FormatResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);


        if($response instanceof  JsonResponse) {
            $data = $response->getData(true);

            if($response->getStatusCode() != 200) {
                if(!isset($data['code'])) {
                    $response->setData(['code'=>1, 'response'=>$data]);
                    return $response;
                }
            }
            if(!isset($data['code'])) {
                $response->setData(['code'=>0, 'response'=>$data]);

            }
            return $response;
        }

        if(is_array($response)) {
            if(!isset($response['code'])) {
                return ['code'=>0,'response'=>$response];
            }
        }

        return $response;
    }
}
