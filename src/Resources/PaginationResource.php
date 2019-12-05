<?php

namespace Cndrsdrmn\ApiFormatResponse\Resources;

use Cndrsdrmn\ApiFormatResponse\Resources\Resource as ApiFormatResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class PaginationResource extends ResourceCollection
{
    /**
     * Meta status code response
     * 
     * @var integer
     */
    protected $status;

    /**
     * Meta message response
     * 
     * @var string
     */
    protected $message;

    /**
     * Create a new resource instance.
     *
     * @param  mixed    $resource
     * @param  int      $status
     * @param  string   $message
     * @return void
     */
    public function __construct($resource, int $status = 200, string $message = '')
    {
        parent::__construct($resource);

        $this->status = $status;

        $this->message = $message;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return $this->resource instanceof AbstractPaginator
                    ? (new ApiFormatResource($this, $this->status, $this->message))->toResponse($request)
                    : parent::toResponse($request);
    }
}