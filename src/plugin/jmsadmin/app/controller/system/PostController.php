<?php

namespace plugin\jmsadmin\app\controller\system;

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\app\service\system\PostService;
use plugin\jmsadmin\app\validate\system\PostValidate;
use plugin\jmsadmin\basic\BasicController;

#[LogInfo(name: "岗位管理")]
class PostController extends BasicController
{
    public function __construct()
    {
        $this->validate = new PostValidate();
        $this->service = new PostService($this->validate);
        parent::__construct();
    }

}