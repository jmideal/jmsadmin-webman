<?php

namespace plugin\jmsadmin\app\controller\system;

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\annotation\UsePermission;
use plugin\jmsadmin\app\service\system\DictTypeService;
use plugin\jmsadmin\app\validate\system\DictTypeValidate;
use plugin\jmsadmin\basic\BasicController;
use plugin\jmsadmin\utils\ApiResult;
use support\Request;
use support\Response;

#[LogInfo(name: "字典管理")]
class DictTypeController extends BasicController
{
    public function __construct()
    {
        $this->validate = new DictTypeValidate();
        $this->service = new DictTypeService($this->validate);
        parent::__construct();
    }

    #[UsePermission("system:common:query")]
    public function optionSelectList(Request $request):Response
    {
        $ret = $this->service->getListWithoutPage([]);
        return ApiResult::success($ret);
    }

}