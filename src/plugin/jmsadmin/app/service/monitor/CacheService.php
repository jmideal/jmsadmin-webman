<?php

namespace plugin\jmsadmin\app\service\monitor;

use plugin\jmsadmin\constant\Constants;

class CacheService
{
    function cacheList()
    {
        return [
            Constants::LOGIN_TOKEN_KEY => "在线用户",
            Constants::CONFIG_KEY => "配置信息",
            Constants::TABLE_INFO_KEY => '表结构信息',
            Constants::USER_PWD_ERR_KEY => '密码错误次数信息',
        ];
    }
    function formatCacheRet($data)
    {
        return [
            "cacheName"     => $data["cacheName"] ?? '',
            "cacheKey"      => $data["cacheKey"] ?? '',
            "cacheValue"    => $data["cacheValue"] ?? '',
            "remark"        => $data["remark"] ?? '',
        ];
    }
}