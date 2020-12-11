<?php

namespace Cndrsdrmn\ApiFormatResponse\Response;

use Cndrsdrmn\ApiFormatResponse\Response\Response as ApiFormatResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Collection;

class Transformer extends ApiFormatResponse
{
	/**
	 * Namespace of resource collection
	 *
	 * @var string
	 */
	const RESOURCE_COLLECTION = '\\App\\Http\\Resources\\Collections';

	/**
	 * Namespace of resource model
	 *
	 * @var string
	 */
	const RESOURCE_MODEL = '\\App\\Http\\Resources\\Models';

	/**
	 * Namespace of resource pagination
	 *
	 * @var string
	 */
	const RESOURCE_PAGINATE = '\\App\\Http\\Resources\\Paginations';

	/**
	 * Get resource data
	 * 
	 * @return mixed
	 */
	public function getData()
	{
		return isset($this->resource['data']) 
			? $this->transform($this->resource['data'])
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
		$this->resource['data'] = $this->transform($data);

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
		$message = $message ?: HttpResponse::$statusTexts[$status];

		$className = class_exists($this->getClaseName($this->resource, self::RESOURCE_PAGINATE))
				? $this->getClaseName($this->resource, self::RESOURCE_PAGINATE)
				: static::DEFAULT_PAGINATE;

        return new $className($this->resource, $status, $message);
	}

	/**
	 * Transform resource
	 * 
	 * @param  mixed $resource
     * @return \Illuminate\Http\JsonResponse
	 */
	protected function transform($resource)
	{
		if ( $resource instanceof Model ) {
			$resource = $this->setResource($resource);
		} elseif ( $resource instanceof Collection ) {
			$resource = $this->setCollectionResource($resource);
		} elseif ( is_array($resource) ) {
			$resource = $this->setArrayResource($resource);
		}

		return $resource;
	}

	/**
	 * Set to pattren array resource
	 * 
	 * @param 	array	$resource
     * @return 	mixed
	 */
	protected function setArrayResource(array $resource)
	{
		return collect($resource)->map(function ($value, $key) {
			return $this->transform($value);
		});
	}

	/**
	 * Set to pattren collection resource
	 * 
	 * @param 	\Illuminate\Support\Collection	$resource
     * @return 	\Illuminate\Http\JsonResponse
	 */
	protected function setCollectionResource(Collection $resource)
	{
		$item = $resource->first();

		if ( $item instanceof Model ) {
			$resource = $this->init($resource, self::RESOURCE_COLLECTION);
		} elseif ( is_array($item) ) {
			$resource = $resource->map(function ($value, $key) {
				return $this->transform($value);
			});
		}

		return $resource;
	}

	/**
	 * Set to pattren resource
	 * 
	 * @param 	\Illuminate\Database\Eloquent\Model $resource
     * @return 	\Illuminate\Http\JsonResponse
	 */
	protected function setResource(Model $resource)
	{
		$resource = $this->init($resource, self::RESOURCE_MODEL);

		return $resource;
	}

	/**
	 * Get class name of resource
	 * 
	 * @param  	\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection 	$resource
	 * @param  	string 																$baseNamespace
     * @return 	string
	 */
	protected function getClaseName($resource, string $baseNamespace)
	{
		$class     = $resource instanceof Model ? $resource : $resource->first();
		$className = '';

		if (!is_null($class)) {
            $namespace = explode('\\', get_class($class));
            $className = sprintf('%s\\%sResource', $baseNamespace, end($namespace));
        }

		return $className;
	}

	/**
	 * Init class resource
	 * 
	 * @param  	\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection 	$resource
	 * @param  	string 																$baseNamespace
     * @return 	\Illuminate\Http\JsonResponse
	 */
	protected function init($resource, string $baseNamespace)
	{
		$className = $this->getClaseName($resource, $baseNamespace);

		if ( class_exists($className) ) {
			$resource = new $className($resource);
		}

		return $resource;
	}
}