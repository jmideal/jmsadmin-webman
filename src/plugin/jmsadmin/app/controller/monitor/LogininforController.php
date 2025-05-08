<?php

namespace plugin\jmsadmin\app\controller\monitor;

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\app\service\monitor\LogininforService;
use plugin\jmsadmin\app\validate\monitor\LogininforValidate;
use plugin\jmsadmin\basic\BasicController;
use plugin\jmsadmin\utils\ApiResult;
use support\Request;
use support\Response;

#[LogInfo(name: "登录日志管理")]
class LogininforController extends BasicController
{
    public function __construct()
    {
        $this->validate = new LogininforValidate();
        $this->service = new LogininforService($this->validate);
        parent::__construct();
    }
    public function allRemove(Request $request):Response
    {
        return $this->service->allRemove() ? ApiResult::success() : ApiResult::error();
    }

    public function lockRemove(Request $request):Response
    {
        $userName = $request->post('userName');
        if (!empty($userName)) {
            $this->service->unLockUserCache($userName);
        }
        return ApiResult::success();
    }
}