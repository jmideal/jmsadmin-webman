<?php

namespace plugin\jmsadmin\app\service\system;

use plugin\jmsadmin\app\model\system\NoticeModel;
use plugin\jmsadmin\basic\BasicService;

class NoticeService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new NoticeModel();
        parent::__construct($validate);
    }

}