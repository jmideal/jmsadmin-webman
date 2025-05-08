<?php

return [
    '' => [
        plugin\jmsadmin\app\middleware\AccessControl::class,
        plugin\jmsadmin\app\middleware\LoginControl::class,
        plugin\jmsadmin\app\middleware\AuthControl::class,
        plugin\jmsadmin\app\middleware\OperationLog::class
    ]
];
