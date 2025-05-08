<?php

namespace plugin\jmsadmin\app\model\scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use plugin\jmsadmin\app\model\system\DeptModel;
use plugin\jmsadmin\app\model\system\UserModel;
use plugin\jmsadmin\constant\Constants;
use plugin\jmsadmin\utils\Util;

class AccessDataScope implements Scope
{
    /**
     * 将作用域应用到给定的 Eloquent 查询构建器
     * data_scope数据范围（1：全部数据权限 2：自定数据权限 3：本部门数据权限 4：本部门及以下数据权限）
     */
    public function apply(Builder $builder, Model $model): void
    {
        //print_r($builder->getQuery()->wheres);
        if (empty($model->getDataLimitColumn())) {
            return;
        }
        $adminInfo = adminInfo();
        if (empty($adminInfo['user_id']) || empty($adminInfo['roles'])) {
            return;
        }
        if (userIsRoot($adminInfo)) {
            return;
        }
        $userId = $adminInfo['user_id'];
        $dept = $adminInfo['dept'];
        //如果未设置部门或部门未启用，则走本人数据权限
        if (empty($dept['status'])) {
            $builder->where($model->getQualifiedDataLimitColumn(), $userId);
            return;
        }
        $roles = $adminInfo['roles'];
        $customRoleIds = [];
        foreach ($roles as $key => $role) {
            if ($role['status'] != 1) {
                continue;
            }
            if (userIsRootRole($role)) {
                return;
            }
            if ($role['data_scope'] == Constants::DATA_SCOPE_ALL) {
                return;
            }
            if ($role['data_scope'] == Constants::DATA_SCOPE_CUSTOM) {
                array_push($customRoleIds, $role['role_id']);
            }
            if ($role['data_scope'] == Constants::DATA_SCOPE_DEPT) {
                $selfDeptId = $dept['dept_id'];
            }
            if ($role['data_scope'] == Constants::DATA_SCOPE_DEPT_AND_CHILD) {
                $selfDeptAndChildId = $dept['dept_id'];
            }
        }
        $deptScope = [];
        //如果存在自定义权限
        if (!empty($customRoleIds)) {
            $ret = Util::getDb()->table("sys_role_dept")->whereIn("role_id", $customRoleIds)->pluck("dept_id")->toArray();
            $deptScope = array_merge($deptScope, $ret);
        }
        if (!empty($selfDeptId)) {
            array_push($deptScope, $selfDeptId);
        }
        if (!empty($selfDeptAndChildId)) {
            $query = Util::getDb()->table("sys_dept")->where("dept_id", $dept['dept_id'])->orWhereRaw("find_in_set( ? , ancestors )", $selfDeptAndChildId);
            if (!empty(DeptModel::DELETED_AT)) {
                $query->whereNull(DeptModel::DELETED_AT);
            }
            $ret = $query->pluck("dept_id")->toArray();
            $deptScope = array_merge($deptScope, $ret);
        }
        //如果未设置任何权限，则走本人权限
        if (empty($deptScope)) {
            $builder->where($model->getQualifiedDataLimitColumn(), $userId);
            return;
        }
        $deptScope = array_unique($deptScope);
        $builder->where(function (Builder $query) use ($userId, $deptScope) {
            $query->where($query->getModel()->getQualifiedDataLimitColumn(), $userId);
            if (!empty($deptScope)) {
                $inQuery = Util::getDb()->table("sys_user")->whereIn("dept_id", $deptScope)->select("user_id");
                if (!empty(UserModel::DELETED_AT)) {
                    $inQuery->whereNull(UserModel::DELETED_AT);
                }
                $query->orWhereIn($query->getModel()->getQualifiedDataLimitColumn(), $inQuery);
            }
        });
    }
}