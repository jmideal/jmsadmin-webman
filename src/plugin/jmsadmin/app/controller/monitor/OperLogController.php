<?php

namespace plugin\jmsadmin\app\controller\monitor;

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\app\service\monitor\OperLogService;
use plugin\jmsadmin\app\validate\monitor\OperLogValidate;
use plugin\jmsadmin\basic\BasicController;
use plugin\jmsadmin\utils\ApiResult;
use support\Request;
use support\Response;

#[LogInfo(name: "操作日志管理")]
class OperLogController extends BasicController
{
    public function __construct()
    {
        $this->validate = new OperLogValidate();
        $this->service = new OperLogService($this->validate);
        parent::__construct();
    }

    public function allRemove(Request $request):Response
    {
        return $this->service->allRemove() ? ApiResult::success() : ApiResult::error();
    }
}