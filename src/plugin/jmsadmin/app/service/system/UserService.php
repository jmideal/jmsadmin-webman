<?php

namespace plugin\jmsadmin\app\service\system;

use plugin\jmsadmin\app\model\system\UserModel;
use plugin\jmsadmin\app\model\system\UserPostModel;
use plugin\jmsadmin\app\model\system\UserRoleModel;
use plugin\jmsadmin\app\service\AuthService;
use plugin\jmsadmin\basic\BasicService;
use plugin\jmsadmin\exception\ApiException;
use plugin\jmsadmin\utils\Util;

class UserService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new UserModel();
        parent::__construct($validate);
    }

    public function getInfo($id)
    {
        $info = $this->model->newQuery()->where('user_id', $id)->firstOrFail()->toArray();
        $deptService = new DeptService();
        $roleService = new RoleService();
        $userRoleService = new UserRoleService();
        $roleIds = $userRoleService->buildQuery([], ['user_id', '=', $id])->pluck('role_id')->toArray();

        $dept = [];
        if (!empty($info['dept_id'])) {
            $dept = $deptService->buildQuery([], ['dept_id', '=', $info['dept_id']])->first();
            $dept = !empty($dept) ? $dept->toArray() : [];
        }
        $userRoles = [];
        if (!empty($roleIds)) {
            $userRoles = $roleService->getListWithoutPage(['role_id' => $roleIds]);
        }
        $info['dept'] = $dept;
        $info['roles'] = $userRoles;
        unset($info['password']);
        return $info;
    }

    public function selectUserList($params)
    {
        $roleId = $params['role_id'] ?? '';
        $deptId = 0;
        if (!empty($params['dept_id'])) {
            $deptId = $params['dept_id'];
            unset($params['dept_id']);
        }
        $query = $this->buildQuery($params, []);
        $deptService = new DeptService();
        $roleService = new RoleService();
        $userRoleService = new UserRoleService();
        if (!empty($deptId)) {
            $child = $deptService->getChildDeptList($deptId);
            $searchDept = array_column($child, 'dept_id');
            array_push($searchDept, $deptId);
        }
        $deptDataScope = getDeptDataScope(false);
        if (empty($deptDataScope)) {
            return ['total' => 0, 'rows' => []];
        }
        if (!empty($searchDept)) {
            $queryDept = array_intersect($searchDept, $deptDataScope);
        } else {
            $queryDept = $deptDataScope;
        }
        $query->whereIn('dept_id', $queryDept);
        if (!empty($roleId)) {
            $inQuery = $userRoleService->model->select("user_id")->where('role_id', $roleId);
            $query->whereIn('user_id', $inQuery);
        }
        $ret = $this->getListWithPage(query : $query);
        $rows = $ret['rows'];
        $deptIds = array_unique(array_column($rows, 'dept_id'));
        $userIds = array_unique(array_column($rows, 'user_id'));
        $depts = $deptService->getListWithoutPage(['dept_id' => $deptIds]);
        $roleList = $roleService->getListWithoutPage([]);
        $userRoles = $userRoleService->model->whereIn('user_id', $userIds)->get()->toArray();

        foreach ($rows as $key => $row) {
            unset($row['password']);
            $dept = [];
            if (!empty($row['dept_id'])) {
                foreach ($depts as $value) {
                    if ($row['dept_id'] == $value['dept_id']) {
                        $dept = $value;
                        break;
                    }
                }
            }
            $roles = [];
            foreach ($userRoles as $ur) {
                if ($row['user_id'] == $ur['user_id']) {
                    foreach ($roleList as $r) {
                        if ($r['role_id'] == $ur['role_id']) {
                            $roles[] = $r;
                        }
                    }
                }
            }
            $row['dept'] = $dept;
            $row['roles'] = $roles;
            $rows[$key] = $row;
        }
        $ret['rows'] = $rows;
        return $ret;
    }

    public function verifyUserRoles($userId, $newRoleIds)
    {
        $haveRoleIds = [];
        if (!empty($userId)) {
            $userRoleService = new UserRoleService();
            $haveRoleIds = $userRoleService->buildQuery([], ['user_id', '=', $userId])->pluck('role_id')->toArray();
        }
        $adminInfo = adminInfo();
        if (userIsRoot($adminInfo)) {
            return;
        }
        $adminRoles = $adminInfo['roles'] ?? [];
        foreach ($adminRoles as $role) {
            if (userIsRootRole($role)) {
                return;
            }
        }
        $adminRoleIds = array_column($adminRoles, 'role_id');
        $roleIds = array_unique(array_merge($adminRoleIds, $haveRoleIds));
        $intersect = array_intersect($roleIds, $newRoleIds);
        if (count($intersect) != count($newRoleIds)) {
            throw new ApiException('您设置了无权限的角色');
        }
    }

    public function beforeInsert($params)
    {
        if (!preg_match('/^\w{2,20}$/i', $params['user_name'])) {
            throw new ApiException("用户名只能包含字母数字和下划线");
        }
        if (!empty($params['role_ids'])) {
            $this->verifyUserRoles(0, $params['role_ids']);
        }
        $deptDataScope = getDeptDataScope(false);
        if (!empty($params['dept_id']) && !in_array($params['dept_id'], $deptDataScope)) {
            throw new ApiException('您无权操作该用户');
        }
        $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
        return parent::beforeInsert($params);
    }
    public function insert($params)
    {
        Util::getDb()->beginTransaction();
        try {
            $userId = parent::insert($params);
            if (!empty($params['post_ids'])) {
                foreach ($params['post_ids'] as $postId) {
                    $userPost = new UserPostModel();
                    $userPost->post_id = $postId;
                    $userPost->user_id = $userId;
                    $userPost->save();
                }
            }
            if (!empty($params['role_ids'])) {
                foreach ($params['role_ids'] as $roleId) {
                    $userRole = new UserRoleModel();
                    $userRole->role_id = $roleId;
                    $userRole->user_id = $userId;
                    $userRole->save();
                }
            }
            Util::getDb()->commit();
        } catch (\Throwable $exception) {
            Util::getDb()->rollBack();
            throw $exception;
        }
        return $userId;
    }

    public function beforeUpdate($params)
    {
        if (isset($params['username']) && !preg_match('/^\w{2,20}$/i', $params['username'])) {
            throw new ApiException("用户名只能包含字母数字和下划线");
        }
        $userId = $params['user_id'] ?? '';
        $adminInfo = adminInfo();
        $this->checkUserAllowed($params, $adminInfo['user_id']);
        $user = $this->model->where('user_id', $userId)->first();
        if (empty($user)) {
            throw new ApiException('参数错误');
        }
        if (!empty($params['role_ids'])) {
            $this->verifyUserRoles($params['user_id'], $params['role_ids']);
        }
        if ($adminInfo['user_id'] != $userId) {
            $deptDataScope = getDeptDataScope(false);
            if (!in_array($user['dept_id'], $deptDataScope)) {
                throw new ApiException('您无权操作该用户');
            }
            if (!empty($params['dept_id']) && !in_array($params['dept_id'], $deptDataScope)) {
                throw new ApiException('您无权操作该用户');
            }
        }
        return parent::beforeUpdate($params);
    }

    public function update($params)
    {
        Util::getDb()->beginTransaction();
        try {
            $userId = $params['user_id'];
            $ret = parent::update($params);
            UserPostModel::where('user_id', $userId)->delete();
            UserRoleModel::where('user_id', $userId)->delete();
            if (!empty($params['post_ids'])) {
                foreach ($params['post_ids'] as $postId) {
                    $userPost = new UserPostModel();
                    $userPost->post_id = $postId;
                    $userPost->user_id = $userId;
                    $userPost->save();
                }
            }
            if (!empty($params['role_ids'])) {
                foreach ($params['role_ids'] as $roleId) {
                    $userRole = new UserRoleModel();
                    $userRole->role_id = $roleId;
                    $userRole->user_id = $userId;
                    $userRole->save();
                }
            }
            Util::getDb()->commit();
        } catch (\Throwable $exception) {
            Util::getDb()->rollBack();
            throw $exception;
        }
        return $ret;
    }

    public function userUpdate($data)
    {
        $adminInfo = adminInfo();
        $userId = $data['user_id'] ?? '';
        $user = $this->model->where('user_id', $userId)->first();
        if (empty($user)) {
            throw new ApiException('参数错误');
        }
        $data['update_by'] = $adminInfo['user_id'];
        $ret = parent::update($data);
        $this->afterUpdate($data);
        return $ret;
    }

    public function beforeDelete($id)
    {
        $id = parent::beforeDelete($id);
        foreach ($id as $k => $v) {
            $this->checkUserAllowed(['user_id' => $v]);
        }
        return $id;
    }

    public function delete($id)
    {
        //修正数据，只操作自己有数据权限的用户
        $deptDataScope = getDeptDataScope(false);
        if (empty($deptDataScope)) {
            throw new ApiException('您无权执行该操作');
        }
        $userId = $this->model->whereIn('user_id', $id)->whereIn('dept_id', $deptDataScope)->pluck('user_id')->toArray();
        Util::getDb()->beginTransaction();
        try {
            parent::delete($userId);
            UserPostModel::whereIn('user_id', $userId)->delete();
            UserRoleModel::whereIn('user_id', $userId)->delete();
            Util::getDb()->commit();
        } catch (\Throwable $exception) {
            Util::getDb()->rollBack();
            throw $exception;
        }
        return true;
    }
    public function afterUpdate($params)
    {
        $authService = new AuthService();
        $authService->refreshCacheByUserOrRole($params['user_id']);
    }
    public function afterDelete($id)
    {

    }

    public function checkUserAllowed($user, $optUserId = 0)
    {
        if (userIsRoot($user)) {
            throw new ApiException('不允许操作超级管理员');
        }
    }
}