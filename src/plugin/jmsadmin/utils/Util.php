<?php

namespace plugin\jmsadmin\utils;

use plugin\jmsadmin\exception\ApiException;
use support\Db;
use support\Redis;

class Util
{
    public static function getDb($connection = null)
    {
        return Db::connection($connection ?: 'plugin.jmsadmin.mysql');
    }

    public static function getRedis($connection = null)
    {
        return Redis::connection($connection ?: 'plugin.jmsadmin.default');
    }
}