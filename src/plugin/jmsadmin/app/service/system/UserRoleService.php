<?php

namespace plugin\jmsadmin\app\service\system;

use plugin\jmsadmin\app\model\system\UserRoleModel;
use plugin\jmsadmin\basic\BasicService;

class UserRoleService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new UserRoleModel();
        parent::__construct($validate);
    }

}