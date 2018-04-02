<?php
namespace App\Http\Middleware;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormatResponse
{
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @param  string|null  $guard
         * @return mixed
         */
        public function handle(Request $request, \Closure $next, $guard = null) :JsonResponse
        {
               $response = $next($request);

               if($response instanceof JsonResponse) {

                       $data = $response->getData(true);

                       if($response->getStatusCode() != 200) {
                               if(!isset($data['code'])) {
                                       $response->setData(['code'=>1,'response'=>$data]);
                                       return $response;
                               }
                       }

                       if(!isset($data['code'])) {
                               $response->setData(['code'=>0,'response'=>$data]);
                       }

                       return $response;

               } else if(is_array($response)) {

                       if(!isset($response['code'])) {
                               $response = ['code'=>0,'response'=>$response];
                       }

                       $response = new JsonResponse($response);

               } else {

                       $response = new JsonResponse(['code'=>0,'response'=>$response]);
               }

               return $response;
        }
}