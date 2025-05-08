<?php
return [
    'default' => [
        'password' => getenv('REDIS_PASSWORD'),
        'host' => getenv('REDIS_HOST'),
        'port' => getenv('REDIS_PORT'),
        'database' => getenv('REDIS_DATABASE'),
        'pool' => [
            'max_connections' => 5,
            'min_connections' => 1,
            'wait_timeout' => 3,
            'idle_timeout' => 60,
            'heartbeat_interval' => 50,
        ],
    ]
];