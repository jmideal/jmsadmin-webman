<?php

namespace plugin\jmsadmin\app\controller\system;

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\annotation\UsePermission;
use plugin\jmsadmin\app\service\system\DeptService;
use plugin\jmsadmin\app\service\system\PostService;
use plugin\jmsadmin\app\service\system\RoleService;
use plugin\jmsadmin\app\service\system\UserPostService;
use plugin\jmsadmin\app\service\system\UserService;
use plugin\jmsadmin\app\validate\system\UserValidate;
use plugin\jmsadmin\basic\BasicController;
use plugin\jmsadmin\utils\ApiResult;
use support\Request;
use support\Response;

#[LogInfo(name: "用户管理")]
class UserController extends BasicController
{
    public function __construct()
    {
        $this->validate = new UserValidate();
        $this->service = new UserService($this->validate);
        parent::__construct();
    }

    public function list(Request $request):Response
    {
        $params = $this->validate->run('search', $request->get());
        $ret = $this->service->selectUserList($params);
        $roleService = new RoleService();
        $ret['roles'] = $roleService->getAll([]);
        return ApiResult::success($ret);
    }

    public function info(Request $request):Response
    {
        $userId = $request->get('userId', '0');
        $info = [];
        if (!empty($userId)) {
            $info = $this->service->getInfo($userId);
            $deptDataScope = getDeptDataScope(false);
            if (!in_array($info['dept_id'], $deptDataScope)) {
                return ApiResult::error("您没有权限操作该用户");
            }
        }
        $adminInfo = adminInfo();
        $postService = new PostService();
        $roleService = new RoleService();
        $userPostService = new UserPostService();
        $posts = $postService->getListWithoutPage([]);
        $haveRoles = $info['roles'] ?? [];
        $adminRoles = $adminInfo['roles'] ?? [];
        $roles = $roleService->rolesMerge($haveRoles, $adminRoles);
        $postIds = $userPostService->buildQuery([], ['user_id', '=', $userId])->pluck('post_id')->toArray();
        $roleIds = [];
        if (!empty($info['roles'])) {
            $roleIds = array_unique(array_column($info['roles'], 'role_id'));
        }
        return ApiResult::success(compact('info', 'posts', 'roles', 'postIds', 'roleIds'));
    }

    public function pwdEdit(Request $request):Response
    {
        $userId = $request->post("userId", "0");
        $userId = intval($userId);
        $password = $request->post("password");
        if (empty($userId) || empty($password)) {
            return ApiResult::error("参数有误");
        }
        if (strlen($password) < 5 || strlen($password) > 20) {
            return ApiResult::error("参数有误");
        }
        if (userIsRoot(['user_id' => $userId])) {
            return ApiResult::error("不允许操作超级管理员用户");
        }
        $data = [
            'user_id' => $userId,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];
        $userService = new UserService();
        $data = $userService->beforeUpdate($data);
        $ret = $userService->userUpdate($data);
        return $ret? ApiResult::success() : ApiResult::error() ;
    }

    #[UsePermission("system:common:query")]
    public function deptTreeList()
    {
        $deptService = new DeptService();
        $deptTreeList = $deptService->selectDeptTreeList();
        return ApiResult::success($deptTreeList);
    }
}