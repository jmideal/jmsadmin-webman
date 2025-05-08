<?php

namespace plugin\jmsadmin\app\service\system;

use plugin\jmsadmin\app\model\system\UserPostModel;
use plugin\jmsadmin\basic\BasicService;

class UserPostService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new UserPostModel();
        parent::__construct($validate);
    }

}