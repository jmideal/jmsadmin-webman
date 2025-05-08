<?php

namespace plugin\jmsadmin\app\controller\system;

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\annotation\UsePermission;
use plugin\jmsadmin\app\service\system\ConfigService;
use plugin\jmsadmin\app\validate\system\ConfigValidate;
use plugin\jmsadmin\basic\BasicController;
use plugin\jmsadmin\utils\ApiResult;
use support\Request;
use support\Response;

#[LogInfo(name: "参数管理")]
class ConfigController extends BasicController
{
    public function __construct()
    {
        $this->validate = new ConfigValidate();
        $this->service = new ConfigService($this->validate);
        parent::__construct();
    }

    #[UsePermission("system:common:query")]
    public function keyInfo(Request $request): Response
    {
        $configKey = $request->get('configKey', '');
        $ret = $this->service->getKeyInfo($configKey);
        return ApiResult::success($ret);
    }
}