<?php

namespace plugin\jmsadmin\app\controller\monitor;

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\annotation\UsePermission;
use plugin\jmsadmin\app\service\monitor\CacheService;
use plugin\jmsadmin\utils\ApiResult;
use plugin\jmsadmin\utils\Util;

#[LogInfo(name: "缓存管理")]
class CacheController
{
    #[UsePermission("monitor:cache:list")]
    function nameList()
    {
        $cacheService = new CacheService();
        $cacheList = $cacheService->cacheList();
        $list = [];
        foreach ($cacheList as $key => $remark) {
            $list[] = $cacheService->formatCacheRet(["cacheName" => $key, "remark" => $remark]);
        }
        return ApiResult::success($list);
    }

    #[UsePermission("monitor:cache:list")]
    function keyList()
    {
        $cacheName = Request()->get("cacheName");
        if (!empty($cacheName)) {
            $cacheService = new CacheService();
            $cacheList = $cacheService->cacheList();
            if (isset($cacheList[$cacheName])) {
                $cacheKeys = Util::getRedis()->keys($cacheName . "*");
                return ApiResult::success($cacheKeys);
            }
        }
        return ApiResult::success();
    }

    #[UsePermission("monitor:cache:list")]
    function cacheInfo()
    {
        $cacheName = Request()->get("cacheName");
        $cacheKey = Request()->get("cacheKey");
        if (!empty($cacheName) && !empty($cacheKey)) {
            $cacheService = new CacheService();
            $cacheList = $cacheService->cacheList();
            if (isset($cacheList[$cacheName])) {
                $cacheValue = Util::getRedis()->get($cacheKey);
                return ApiResult::success($cacheService->formatCacheRet(["cacheName" => $cacheName, "cacheKey" => $cacheKey , "cacheValue" => $cacheValue]));
            }
        }
        return ApiResult::success();
    }

    #[UsePermission("monitor:cache:manage")]
    function cacheNameRemove()
    {
        $cacheName = Request()->post("cacheName");
        if (!empty($cacheName)) {
            $cacheService = new CacheService();
            $cacheList = $cacheService->cacheList();
            if (isset($cacheList[$cacheName])) {
                $cacheKeys = Util::getRedis()->keys($cacheName . "*");
                foreach ($cacheKeys as $cacheKey) {
                    Util::getRedis()->del($cacheKey);
                }
            }
        }
        return ApiResult::success();
    }

    #[UsePermission("monitor:cache:manage")]
    function cacheKeyRemove()
    {
        $cacheKey = Request()->post("cacheKey", '');
        $idx = strpos($cacheKey, ":");
        if ($idx === false || $idx == 0 || substr($cacheKey, -1) == ':') {
            return ApiResult::error('键名有误');
        }
        $cacheName = substr($cacheKey, 0, $idx) . ':';
        $cacheService = new CacheService();
        $cacheList = $cacheService->cacheList();
        if (isset($cacheList[$cacheName])) {
            Util::getRedis()->del($cacheKey);
        }
        return ApiResult::success();
    }

    #[UsePermission("monitor:cache:manage")]
    function cacheAllRemove()
    {
        $cacheService = new CacheService();
        $cacheList = $cacheService->cacheList();
        foreach ($cacheList as $key => $remark) {
            $cacheKeys = Util::getRedis()->keys($key . "*");
            foreach ($cacheKeys as $cacheKey) {
                Util::getRedis()->del($cacheKey);
            }
        }
        return ApiResult::success();
    }
}