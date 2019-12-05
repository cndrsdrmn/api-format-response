<?php

namespace Cndrsdrmn\ApiFormatResponse\Response;

use Cndrsdrmn\ApiFormatResponse\Contracts\FormatResponse;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Collection;

abstract class Response implements Arrayable, FormatResponse
{
	/**
	 * Default namespace of resource pagination
	 *
	 * @var string
	 */
	const DEFAULT_PAGINATE = '\\Cndrsdrmn\\ApiFormatResponse\\Resources\\PaginationResource';

	/**
	 * Resource for transform
	 * 
	 * @var array
	 */
	protected $resource;

	/**
	 * Response for transform
	 * 
	 * @var string
	 */
	protected $response;

	/**
	 * Instance of Tranformer
	 * 
	 * @param array  $resource
	 * @param string $response
	 */
	public function __construct($resource = [], string $response = 'success')
	{
		$this->resource = $resource;
		$this->response = $response;
	}

	/**
	 * Get response
	 * 
	 * @return array
	 */
	public function response():array
	{
		return [
            'data' => $this->getData(),
            'meta' => [
            	'code' => $this->getCode(),
            	'message' => $this->getMessage(),
            ],
        ];
	}

	/**
	 * Get response code
	 * 
	 * @return int
	 */
	public function getCode():int
	{
		return isset($this->resource['code'])
            ? $this->resource['code']
            : ($this->response == 'success' ? HttpResponse::HTTP_OK : HttpResponse::HTTP_BAD_REQUEST);
	}

	/**
	 * Set response code
	 * 
	 * @param  int $code
	 * @return $this
	 */
	public function setCode(int $code)
	{
		$this->resource['code'] = $code;

		return $this;
	}

	/**
	 * Get resource data
	 * 
	 * @return mixed
	 */
	public function getData()
	{
		return isset($this->resource['data']) 
			? $this->resource['data']
			: (object) null;
	}

	/**
	 * Set resource data
	 * 
	 * @param  mixed $data
	 * @return $this
	 */
	public function setData($data)
	{
		$this->resource['data'] = $data;

		return $this;
	}

	/**
	 * Get response message
	 * 
	 * @return string
	 */
	public function getMessage():string
	{
		return isset($this->resource['message']) ? $this->resource['message'] : HttpResponse::$statusTexts[$this->getCode()];
	}

	/**
	 * Set response message
	 * 
	 * @param  string $message
	 * @return $this
	 */
	public function setMessage(string $message)
	{
		$this->resource['message'] = $message;

		return $this;
	}

	/**
	 * Response to pagination transformer
	 * 
     * @param  integer	$status
     * @param  string	$message
     * @return \Illuminate\Http\JsonResponse
	 */
	public function toPaginate(int $status = 200, string $message = '')
	{
		$className = self::DEFAULT_PAGINATE;

		$message = $message ?: HttpResponse::$statusTexts[$status];

		$resource = new $className($this->resource, $status, $message);

		return $resource;
	}

	/**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
    	return $this->response();
    }
}