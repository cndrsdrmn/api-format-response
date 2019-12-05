## Laravel / Lumen API Format Response

Add `api-request` channel on `config/logging.php`:
```
    'channels' => [
        ...
        'api-request' => [
            'driver'    => 'daily',
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'format' => "[%datetime%] %channel%.%level_name%: %message% %context%\n",
            ],
            'path'      => storage_path('logs/api-request/api-request.log'),
            'level'     => 'debug',
            'days'      => 14,
        ]
        ...
    ]
```