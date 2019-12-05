<?php

if (! function_exists('api')) {
    /**
     * Return a new api format response from the application.
     *
     * @param  string  $content
     * @param  int     $status
     * @param  array   $headers
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    function api($content = '', $status = 200, array $headers = [])
    {
        $factory = app(Cndrsdrmn\ApiFormatResponse\ResponseFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($content, $status, $headers);
    }
}