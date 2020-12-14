<?php

namespace Cndrsdrmn\ApiFormatResponse;

use Cndrsdrmn\ApiFormatResponse\Contracts\ResponseFactory as FactoryContract;
use Cndrsdrmn\ApiFormatResponse\Response\Basic;
use Cndrsdrmn\ApiFormatResponse\Response\Transformer;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Routing\ResponseFactory as BaseResponseFactory;

class ResponseFactory extends BaseResponseFactory implements FactoryContract
{
    /**
     * Return a new JSON response from the application.
     *
     * @param  array        $data
     * @param  integer      $status
     * @param  array        $headers
     * @param  integer      $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function failed($data = [], int $status = 400, array $headers = [], $options = 0)
    {
        $payloads = compact('data', 'status', 'headers', 'options');

        return $this->sendResponse('success', $payloads, false);
    }

    /**
     * Return a new JSON response from the application with transform / resources laravel.
     *
     * @param  array        $data
     * @param  integer      $status
     * @param  array        $headers
     * @param  integer      $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function failedTf($data = [], int $status = 400, array $headers = [], $options = 0)
    {
        $payloads = compact('data', 'status', 'headers', 'options');

        return $this->sendResponse('success', $payloads);
    }

    /**
     * Return a new JSON response from the application.
     *
     * @param  array        $data
     * @param  integer      $status
     * @param  array        $headers
     * @param  integer      $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], int $status = 200, array $headers = [], $options = 0)
    {
        $payloads = compact('data', 'status', 'headers', 'options');

        return $this->sendResponse('success', $payloads, false);
    }

    /**
     * Return a new JSON response from the application with transform / resources laravel.
     *
     * @param  array        $data
     * @param  integer      $status
     * @param  array        $headers
     * @param  integer      $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function successTf($data = [], int $status = 200, array $headers = [], $options = 0)
    {
        $payloads = compact('data', 'status', 'headers', 'options');

        return $this->sendResponse('success', $payloads);
    }

    /**
     * Return a new JSON response pagination from the application.
     * 
     * @param  \Illuminate\Pagination\AbstractPaginator  $collections
     * @param  integer                                      $status
     * @param  string                                       $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginate(AbstractPaginator $collections, int $status = 200, string $message = '')
    {
        $format = $this->getFormat('success', $collections);

        return $format->toPaginate($status, $message);
    }

    /**
     * Get format response
     * 
     * @param  string   $response
     * @param  array    $options
     * @param  boolean  $transform
     * @return Cndrsdrmn\ApiFormatResponse\Response\Transformer|Cndrsdrmn\ApiFormatResponse\Response\Basic
     */
    protected function getFormat($response, $options, $transform = true)
    {
        return $transform
            ? new Transformer($options, $response)
            : new Basic($options, $response);
    }

    /**
     * Send response
     * 
     * @param  string   $response
     * @param  array    $payloads
     * @param  boolean  $transform
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResponse(string $response, array $payloads, bool $transform = true)
    {
        extract($payloads, EXTR_PREFIX_SAME, 'wddx');

        $data['code'] = isset($data['code']) ? $data['code'] : $status;
        
        return $this->json(
            $this->getFormat($response, $data, $transform), $data['code'], $headers, $options
        );
    }
}
