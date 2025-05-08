<?php

namespace plugin\jmsadmin\app\controller\system;

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\app\service\system\NoticeService;
use plugin\jmsadmin\app\validate\system\NoticeValidate;
use plugin\jmsadmin\basic\BasicController;

#[LogInfo(name: "通知公告管理")]
class NoticeController extends BasicController
{
    public function __construct()
    {
        $this->validate = new NoticeValidate();
        $this->service = new NoticeService($this->validate);
        parent::__construct();
    }
}