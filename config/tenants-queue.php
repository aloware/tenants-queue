<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TenantsQueue Status
    |--------------------------------------------------------------------------
    |
    | Set this option to false if you don't want queues to be processed fairly.
    */
    'enabled' => env('TENANTS_QUEUE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | TenantsQueue Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will get attached onto each TenantsQueue route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Tenants Default Worker Name
    |--------------------------------------------------------------------------
    |
    | This is the default worker name that will be used for tenants-queue.
    |
    */
    'default_worker_name' => 'default',

    /*
    |--------------------------------------------------------------------------
    | TenantsQueue Queue Tries
    |--------------------------------------------------------------------------
    |
    | After a jobs how many times it should requeue? You can specify the
    | number of tries here.
    |
    */
    'queues' => [
        'default' => ['tries' => 3],
    ],

    /*
    |--------------------------------------------------------------------------
    | TenantsQueue Redis DB
    |--------------------------------------------------------------------------
    |
    | If you have a seperated redis connection for tenants-queue, you can specify
    | Redis connection name here
    */
    'database'   => env('TENANTS_QUEUE_REDIS_DB', 'default'),

    /*
    |--------------------------------------------------------------------------
    | TenantsQueue Redis DB
    |--------------------------------------------------------------------------
    |
    | If you want to have a custom redis key prefix for your tenants-queue queues
    | you can set it here
    */
    'key_prefix' => env('TENANTS_QUEUE_KEY_PREFIX', 'tenants-queue'),

    /*
    |--------------------------------------------------------------------------
    | TenantsQueue Tenants Size
    |--------------------------------------------------------------------------
    |
    | This is the amount of tenants that will be processed.
    | a random number will be picked from 0 to this number.
    */
    'tenants_size' => env('TENANTS_SIZE', 100),

    /*
    |--------------------------------------------------------------------------
    | TenantsQueue Refresh Stats
    |--------------------------------------------------------------------------
    | TenantsQueue Stats Configurations
    |
    */
    'stats' => [
        'enabled' => env('TENANTS_QUEUE_REFRESH_STATS_ENABLED', true),
    ],
];
