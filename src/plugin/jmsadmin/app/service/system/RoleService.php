<?php

namespace plugin\jmsadmin\app\service\system;

use plugin\jmsadmin\app\model\system\RoleModel;
use plugin\jmsadmin\app\service\AuthService;
use plugin\jmsadmin\basic\BasicService;
use plugin\jmsadmin\constant\Constants;
use plugin\jmsadmin\exception\ApiException;
use plugin\jmsadmin\utils\Util;

class RoleService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new RoleModel();
        parent::__construct($validate);
    }
    public function getInfo($id)
    {
        return $this->model->where('role_id', $id)->firstOrFail()->toArray();
    }

    public function getList($params)
    {
        $isSuperScope = adminIsSuperScope(adminInfo());
        if ($isSuperScope) {
            return parent::getListWithPage($params);
        }
        return ['rows' => [], 'total' => 0];
    }
    public function getAll($params)
    {
        $adminInfo = adminInfo();
        if (userIsRoot($adminInfo)) {
            return parent::getListWithoutPage($params);
        }
        if (empty($adminInfo['roles'])) {
            return [];
        }
        $ids = [];
        foreach ($adminInfo['roles'] as $role) {
            if ($role['status'] != 1) {
                continue;
            }
            if (userIsRootRole($role)) {
                return parent::getListWithoutPage($params);
            }
            $ids[] = $role['role_id'];
        }
        if (empty($ids)) {
            return [];
        } else {
            $params['role_id'] = $ids;
        }
        return parent::getListWithoutPage($params);
    }

    public function rolesMerge($roles1, $roles2)
    {
        foreach ($roles1 as $role) {
            if (userIsRootRole($role)) {
                return $this->getListWithoutPage([]);
            }
        }
        foreach ($roles2 as $role) {
            if (userIsRootRole($role)) {
                return $this->getListWithoutPage([]);
            }
        }
        $roleIds = array_column($roles1, 'role_id');
        foreach ($roles2 as $role) {
            if (!in_array($role['role_id'], $roleIds)) {
                array_push($roles1, $role);
            }
        }
        return $roles1;
    }

    public function beforeInsert($params)
    {
        $isSuperScope = adminIsSuperScope();
        if (!$isSuperScope) {
            throw new ApiException("无权执行该操作");
        }
        if (empty($params['menu_ids']) || !is_array($params['menu_ids']) || !verifyIntArray($params['menu_ids'])) {
            throw new ApiException("必须选择菜单权限");
        }
        $params = parent::beforeInsert($params);
        return $params;
    }

    public function insert($params)
    {
        Util::getDb()->beginTransaction();
        try {
            $role_id = parent::insert($params);
            $params['role_id'] = $role_id;
            $batchData = $this->batchRoleMenu($params);
            if (!empty($batchData)) {
                $roleMenuService = new RoleMenuService();
                $roleMenuService->batchInsertRoleMenu($batchData);
            }
            Util::getDb()->commit();
        } catch (\Throwable $exception) {
            Util::getDb()->rollBack();
            throw $exception;
        }
        return $role_id;
    }

    public function beforeUpdate($params)
    {
        $this->checkRoleAllowed($params);
        $isSuperScope = adminIsSuperScope();
        if (!$isSuperScope) {
            throw new ApiException("无权执行该操作");
        }
        if (empty($params['menu_ids']) || !is_array($params['menu_ids']) || !verifyIntArray($params['menu_ids'])) {
            throw new ApiException("必须选择菜单权限");
        }
        parent::beforeUpdate($params);
        return $params;
    }

    public function update($params)
    {
        Util::getDb()->beginTransaction();
        try {
            parent::update($params);
            $roleMenuService = new RoleMenuService();
            $roleMenuService->deleteRoleMenuByRoleIds($params['role_id']);
            $batchData = $this->batchRoleMenu($params);
            if (!empty($batchData)) {
                $roleMenuService->batchInsertRoleMenu($batchData);
            }
            Util::getDb()->commit();
        } catch (\Throwable $exception) {
            Util::getDb()->rollBack();
            throw $exception;
        }
        return $params['role_id'];
    }

    public function preDataScopeUpdate($params)
    {
        $this->checkRoleAllowed($params);
        $isSuperScope = adminIsSuperScope();
        if (!$isSuperScope) {
            throw new ApiException("无权执行该操作");
        }
        return parent::beforeUpdate($params);
    }

    public function dataScopeUpdate($params)
    {
        Util::getDb()->beginTransaction();
        try {
            parent::update($params);
            $roleDeptService = new RoleDeptService();
            $roleDeptService->deleteRoleDeptByRoleIds($params['role_id']);
            $deptIds = $params['dept_ids'] ?? [];
            $batchData = [];
            foreach ($deptIds as $deptId) {
                $batchData[] = [
                    'role_id' => $params['role_id'],
                    'dept_id' => $deptId
                ];
            }
            if (!empty($batchData)) {
                $roleDeptService->batchInsertRoleDept($batchData);
            }
            Util::getDb()->commit();
        } catch (\Throwable $exception) {
            Util::getDb()->rollBack();
            throw $exception;
        }
        $this->afterUpdate($params);
        return $params['role_id'];
    }

    public function batchRoleMenu($params)
    {
        $batchData = [];
        if (!empty($params['menu_ids'])) {
            foreach ($params['menu_ids'] as $menuId) {
                $batchData[] = [
                    'role_id' => $params['role_id'],
                    'menu_id' => $menuId
                ];
            }
        }
        return $batchData;
    }

    public function checkRoleAllowed($role)
    {
        if (userIsRootRole($role)) {
            throw new ApiException('不允许操作超级管理员角色');
        }
    }

    public function getDataScopeList($idx)
    {
        $list = [
            Constants::DATA_SCOPE_ALL => ['value' => Constants::DATA_SCOPE_ALL, 'label' => '全部数据权限'],
            Constants::DATA_SCOPE_CUSTOM => ['value' => Constants::DATA_SCOPE_CUSTOM, 'label' => '自定数据权限'],
            Constants::DATA_SCOPE_DEPT => ['value' => Constants::DATA_SCOPE_DEPT, 'label' => '本部门数据权限'],
            Constants::DATA_SCOPE_DEPT_AND_CHILD => ['value' => Constants::DATA_SCOPE_DEPT_AND_CHILD, 'label' => '本部门及以下数据权限'],
            Constants::DATA_SCOPE_SELF => ['value' => Constants::DATA_SCOPE_SELF, 'label' => '仅本人数据权限'],
        ];
        if (empty($idx)) {
            return array_values($list);
        }
        if (empty($list[$idx])) {
            return [];
        }
        return [$list[$idx]];
    }

    public function afterUpdate($params)
    {
        $authService = new AuthService();
        $authService->refreshCacheByUserOrRole([], $params['role_id']);
    }

    public function beforeDelete($id)
    {
        $id = parent::beforeDelete($id);
        foreach ($id as $k => $v) {
            $this->checkRoleAllowed(['role_id' => $v]);
        }
        return $id;
    }
}