<?php

namespace Cndrsdrmn\ApiFormatResponse\Contracts;

use Illuminate\Contracts\Routing\ResponseFactory as BaseContract;
use Illuminate\Pagination\AbstractPaginator;

interface ResponseFactory extends BaseContract
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
    public function failed($data = [], int $status = 400, array $headers = [], $options = 0);

    /**
     * Return a new JSON response from the application with transform / resources laravel.
     *
     * @param  array        $data
     * @param  integer      $status
     * @param  array        $headers
     * @param  integer      $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function failedTf($data = [], int $status = 400, array $headers = [], $options = 0);

    /**
     * Return a new JSON response from the application.
     *
     * @param  array        $data
     * @param  integer      $status
     * @param  array        $headers
     * @param  integer      $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], int $status = 200, array $headers = [], $options = 0);

    /**
     * Return a new JSON response from the application with transform / resources laravel.
     *
     * @param  array        $data
     * @param  integer      $status
     * @param  array        $headers
     * @param  integer      $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function successTf($data = [], int $status = 200, array $headers = [], $options = 0);

    /**
     * Return a new JSON response pagination from the application.
     * 
     * @param  \Illuminate\Pagination\AbstractPaginator  $collections
     * @param  integer                                      $status
     * @param  string                                       $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginate(AbstractPaginator $collections, int $status = 200, string $message = '');
}