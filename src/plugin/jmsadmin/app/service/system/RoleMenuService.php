<?php

namespace plugin\jmsadmin\app\service\system;

use plugin\jmsadmin\app\model\system\RoleMenuModel;
use plugin\jmsadmin\basic\BasicService;

class RoleMenuService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new RoleMenuModel();
        parent::__construct($validate);
    }
    public function batchInsertRoleMenu($batchData)
    {
        return $this->model::insert($batchData);
    }

    public function deleteRoleMenuByRoleIds($roleIds)
    {
        $roleIds = parent::beforeDelete($roleIds);
        return $this->model->whereIn('role_id', $roleIds)->delete();
    }
}