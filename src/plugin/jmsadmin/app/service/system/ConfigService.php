<?php

namespace plugin\jmsadmin\app\service\system;

use plugin\jmsadmin\app\model\system\ConfigModel;
use plugin\jmsadmin\basic\BasicService;
use plugin\jmsadmin\constant\Constants;
use plugin\jmsadmin\utils\Util;

class ConfigService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new ConfigModel();
        parent::__construct($validate);
    }

    public function getKeyInfo($configKey)
    {
        $cacheKey = Constants::CONFIG_KEY . $configKey;
        $value = Util::getRedis()->get($cacheKey);
        if (is_null($value)) {
            $ret = $this->model->where('config_key', $configKey)->first();
            $value = $ret ? $ret->config_value : '' ;
            if ($value !== '') {
                $dataCacheExpire = config('plugin.jmsadmin.app.data_cache_expire');
                Util::getRedis()->setex($cacheKey, $dataCacheExpire, $value);
            }
        }
        return $value;
    }

}