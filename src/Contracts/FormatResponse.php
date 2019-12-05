<?php

namespace Cndrsdrmn\ApiFormatResponse\Contracts;

interface FormatResponse
{
	/**
	 * Get response
	 * 
	 * @return array
	 */
	public function response();

	/**
	 * Get response code
	 * 
	 * @return int
	 */
	public function getCode();

	/**
	 * Set response code
	 * 
	 * @param int $code
	 * @return \App\Classes\ResponseTransformer
	 */
	public function setCode(int $code);

	/**
	 * Get resource data
	 * 
	 * @return mixed
	 */
	public function getData();

	/**
	 * Set resource data
	 * 
	 * @param mixed $data
	 * @return \App\Classes\ResponseTransformer
	 */
	public function setData($data);

	/**
	 * Get response message
	 * 
	 * @return string
	 */
	public function getMessage();

	/**
	 * Set response message
	 * 
	 * @param string $message
	 * @return \App\Classes\ResponseTransformer
	 */
	public function setMessage(string $message);

	/**
	 * Response to pagination transformer
	 * 
     * @param  integer	$status
     * @param  string	$message
     * @return \Illuminate\Http\JsonResponse
	 */
	public function toPaginate(int $status = 200, string $message = '');
}