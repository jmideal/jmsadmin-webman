<?php

return [
    'default' => [
        'handlers' => [
            [
                'class' => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    runtime_path() . '/logs/jmsadmin.log',
                    7,
                    (int)getenv('LOG_LEVEL'),
                ],
                'formatter' => [
                    'class' => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [null, 'Y-m-d H:i:s', true],
                ],
            ]
        ],
    ],
    'console' => [
        'handlers' => [
            [
                'class' => Monolog\Handler\StreamHandler::class,
                'constructor' => [
                    'php://stdout',
                    (int)getenv('LOG_LEVEL'),
                ],
                'formatter' => [
                    'class' => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [null, 'Y-m-d H:i:s.u', true],
                ],
            ]
        ],
    ],
];
