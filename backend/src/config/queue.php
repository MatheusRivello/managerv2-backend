<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Laravel's queue API supports an assortment of back-ends via a single
    | API, giving you convenient access to each back-end using the same
    | syntax for every one. Here you may define a default connection.
    |
    */

    'default' => env('QUEUE_CONNECTION', 'redis'),
    

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection information for each server that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with Laravel. You are free to add more.
    |
    | Drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
    |
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 90,
            'after_commit' => false,
        ],

        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => 'localhost',
            'queue' => 'default',
            'retry_after' => 90,
            'block_for' => 0,
            'after_commit' => false,
        ],

        'sqs' => [
            'driver' => 'sqs',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SQS_QUEUE', 'default'),
            'suffix' => env('SQS_SUFFIX'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'after_commit' => false,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => '{default}',
            'timeout' => 3600,
            'retry_after' => 1800, // Se o job não for processado em 30 minutos, ocorrerá uma nova tentativa
        ],

        'integracao' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'integracao',
            'timeout' => 3600, // 30 minutos
            'retry_after' => 300, // a cada 5 minutos
            'tries' => 12,
            'sleep' => 3 // 3 segundos
        ],

        'pedido' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'pedido',
            'timeout' => 30, // 30 segundos
            'retry_after' => 10, // a cada 10 segundos
            'tries' => 6,
            'sleep' => 3 // 3 segundos
        ],

        'cliente' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'cliente',
            'timeout' => 30, // 30 segundos
            'retry_after' => 10, // a cada 10 segundos
            'tries' => 6,
            'sleep' => 3 // 3 segundos
        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'system'),
        'table' => 'failed_jobs',
    ],

];
