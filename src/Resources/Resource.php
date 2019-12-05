<?php

namespace Cndrsdrmn\ApiFormatResponse\Resources;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse as BaseResource;

class Resource extends BaseResource
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
     * Gather the meta data for the response.
     *
     * @param  array  $paginated
     * @return array
     */
    protected function meta($paginated)
    {
        $meta = parent::meta($paginated);

        return array_merge($meta, [
            'code'      => $this->status,
            'message'   => $this->message,
        ]);
    }
}
