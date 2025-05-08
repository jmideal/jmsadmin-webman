<?php

namespace plugin\jmsadmin\app\controller\system;

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\annotation\UsePermission;
use plugin\jmsadmin\app\service\system\DictDataService;
use plugin\jmsadmin\app\validate\system\DictDataValidate;
use plugin\jmsadmin\basic\BasicController;
use plugin\jmsadmin\utils\ApiResult;
use support\Request;
use support\Response;

#[LogInfo(name: "字典数据管理")]
#[UsePermission("system:dictData:manage")]
class DictDataController extends BasicController
{
    public function __construct()
    {
        $this->validate = new DictDataValidate();
        $this->service = new DictDataService($this->validate);
        parent::__construct();
    }

    #[UsePermission("system:common:query")]
    public function all(Request $request):Response
    {
        $dictType = $request->get('dictType', '');
        $data = [$dictType];
        if (!empty($dictType)) {
            $dictDataService = new DictDataService();
            $data = $dictDataService->selectDictDataListByType($dictType);
        }
        return ApiResult::success($data);
    }
}