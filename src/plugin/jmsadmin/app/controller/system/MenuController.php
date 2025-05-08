<?php

namespace plugin\jmsadmin\app\controller\system;

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\annotation\UsePermission;
use plugin\jmsadmin\app\service\system\MenuService;
use plugin\jmsadmin\app\validate\system\MenuValidate;
use plugin\jmsadmin\basic\BasicController;
use plugin\jmsadmin\utils\ApiResult;
use plugin\jmsadmin\utils\Convert;
use support\Request;
use support\Response;

#[LogInfo(name: "菜单管理")]
class MenuController extends BasicController
{
    public function __construct()
    {
        $this->validate = new MenuValidate();
        $this->service = new MenuService($this->validate);
        parent::__construct();
    }
    public function list(Request $request):Response
    {
        $menuName = $request->get('menuName', '');
        $status = $request->get('status', '');
        $withButton = $request->get('withButton', '1');
        $withButton = in_array($withButton, ['1', '0']) ? $withButton : '1';
        $adminInfo = adminInfo();
        $data = $this->service->selectMenuList($adminInfo, ['menu_name' => $menuName, 'status' => $status], Convert::toBool($withButton, true));
        return ApiResult::success($data);
    }

    public function info(Request $request):Response
    {
        $menuId = $request->get("menuId");
        if (empty($menuId) || !is_numeric($menuId)) {
            return ApiResult::error("参数有误");
        }
        $info = $this->service->getInfo($menuId);
        return ApiResult::success($info);
    }

    #[UsePermission("system:common:query")]
    public function treeList()
    {
        $adminInfo = adminInfo();
        $menus = $this->service->selectMenuList($adminInfo, [], true);
        $menuTree = $this->service->buildMenuTree($menus, 0);
        return ApiResult::success($menuTree);
    }

    #[UsePermission("system:common:query")]
    public function roleMenuTreeList(Request $request):Response
    {
        $roleId = $request->get("roleId", "0");
        if (empty($roleId) || !is_numeric($roleId)) {
            return ApiResult::error("参数有误");
        }
        $adminInfo = adminInfo();
        $menus = $this->service->selectMenuList($adminInfo, [], true);
        $haveMenu = $this->service->selectMenuListByRoleId($roleId);
        $menus = $this->service->menusMerge($menus, $haveMenu);
        $haveMenuIds = array_column($haveMenu, 'menu_id');
        $apiResult = ApiResult::success(['checkedKeys' => $haveMenuIds, 'menus' => $this->service->buildMenuTree($menus, 0)]);
        return $apiResult;
    }
}