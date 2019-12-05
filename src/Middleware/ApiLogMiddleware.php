<?php

namespace Cndrsdrmn\ApiFormatResponse\Middleware;

use Closure;
use Cndrsdrmn\ApiFormatResponse\Response\Response as ApiFormatResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ApiLogMiddleware
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

        Log::channel('api-request')->info($request->getPathInfo(), [
            'request_body'      => $request->all(),
            'request_headers'   => $request->header(),
            'response'          => $this->resolveResponse($response),
            'endpoint' => $request->getPathInfo(),
            'url'      => $request->url(),
            'full_url' => $request->fullUrl(),
        ]);

        return $response;
    }

    /**
     * Checking response is invalid
     * 
     * @param  mixed  $response
     * @return boolean
     */
    protected function isInvalidResponse($response)
    {
        return is_string($response) || is_null($response);
    }

    /**
     * Resolve response
     * 
     * @param  mixed $response
     * @return mixed
     */
    protected function resolveResponse($response)
    {
        $response = $this->resolveData($response);

        if ( $this->isInvalidResponse($response) ) return $response;

        if ( array_key_exists('data', $response) ) {
            $response['data'] = $this->resolveData($response['data']);
        }

        return $response;
    }

    /**
     * Resolve response data
     * 
     * @param  mixed $data
     * @return mixed
     */
    protected function resolveData($data)
    {
        switch ( true ) {
            case $data instanceof HttpResponse:
                return $data->original;

            case $data instanceof JsonResponse:
                return $data->getData(true);

            case $data instanceof JsonResource:
            case $data instanceof Collection:
                return $data->jsonSerialize();

            case $data instanceof ApiFormatResponse:
                return $data->toArray();

            default:
                return $data;
        }
    }
}
