<?php

namespace plugin\jmsadmin\app\service\system;

use plugin\jmsadmin\app\model\system\MenuModel;
use plugin\jmsadmin\basic\BasicService;
use plugin\jmsadmin\constant\Constants;
use plugin\jmsadmin\exception\ApiException;
use plugin\jmsadmin\utils\Convert;

class MenuService extends BasicService
{

    public function __construct($validate = null)
    {
        $this->model = new MenuModel();
        parent::__construct($validate);
    }

    public function getInfo($id)
    {
        return $this->model::select(['menu_id', 'menu_name', 'parent_id', 'order_num', 'path', 'component', 'query', 'route_name', 'is_frame', 'is_cache', 'menu_type', 'visible', 'status', 'icon', 'create_time'])
            ->selectRaw("ifnull(perms,'') as perms")
            ->where('menu_id', $id)
            ->firstOrFail()->toArray();
    }

    public function beforeDelete($id)
    {
        $id = parent::beforeDelete($id);
        foreach ($id as $val) {
            if ($this->hasChild($val)) {
                throw new ApiException("存在子级菜单，不允许删除");
            }
        }
        return $id;
    }

    public function hasChild($id)
    {
        return $this->model->where(['parent_id' => $id])->count() > 0;
    }

    public function selectMenuList($user, $params = [], $withButton = false)
    {
        $query = $this->model->setTable($this->model->getTable() . " as m")->newQuery();
        if (!empty($params['menu_name'])) {
            $query->where('m.menu_name', 'like', '%'.$params['menu_name'].'%');
        }
        if (isset($params['status']) && in_array($params['status'], ['0', '1'])) {
            $query->where('m.status', $params['status']);
        } else {
            $query->where('m.status', '1');
        }
        if (!$withButton) {
            $query->whereRaw("m.menu_type in ('M', 'C')");
        }

        if (adminIsSuperScope($user)) {
            $menus = $query->select(['m.menu_id', 'm.parent_id', 'm.menu_name', 'm.path', 'm.component', 'm.query', 'm.route_name', 'm.visible', 'm.status', 'm.is_frame', 'm.is_cache', 'm.menu_type', 'm.icon', 'm.order_num', 'm.create_time'])
                ->selectRaw("ifnull(m.perms,'') as perms")
                ->orderBy('m.parent_id', 'asc')
                ->orderBy('m.order_num', 'asc')
                ->distinct()
                ->get()
                ->toArray();
        } else {
            $menus = $query->select(['m.menu_id', 'm.parent_id', 'm.menu_name', 'm.path', 'm.component', 'm.query', 'm.route_name', 'm.visible', 'm.status', 'm.is_frame', 'm.is_cache', 'm.menu_type', 'm.icon', 'm.order_num', 'm.create_time'])
                ->selectRaw("ifnull(m.perms,'') as perms")
                ->leftJoin("sys_role_menu as rm", "m.menu_id", "=", "rm.menu_id")
                ->leftJoin("sys_user_role as ur", "rm.role_id", "=", "ur.role_id")
                ->leftJoin("sys_role as ro", "ur.role_id", "=", "ro.role_id")
                ->leftJoin("sys_user as u", "ur.user_id", "=", "u.user_id")
                ->where('u.user_id', $user['user_id'])
                ->distinct()
                ->orderBy("m.parent_id")
                ->orderBy("m.order_num")
                ->get()
                ->toArray();
        }
        return $menus;
    }

    public function selectMenuTreeByUser($user)
    {
        $menus = $this->selectMenuList($user);
        return $this->buildMenuTree($menus);
    }
    public function buildMenuTree($menus, $parentId = 0)
    {
        $menuTree = [];
        $menuGroupByPid = [];
        foreach ($menus as $key => $menu) {
            $menuGroupByPid[$menu['parent_id']][] = $menu;
        }
        return $this->getChildren($menuGroupByPid, $parentId);
    }
    public function getChildren($menus, $parentId): array
    {
        if (isset($menus[$parentId])) {
            $menuTree = [];
            foreach ($menus[$parentId] as $menu) {
                $children = $this->getChildren($menus, $menu['menu_id']);
                if (!empty($children)) {
                    $menu['children'] = $children;
                }
                $menuTree[] = $menu;
            }
            return $menuTree;
        }
        return [];
    }

    public function selectMenuListByRoleId($roleId)
    {
        $roleService = new RoleService();
        $role = $roleService->getInfo($roleId);

        $query = $this->model->setTable($this->model->getTable() . " as m")->newQuery();
        $query->select(['m.menu_id', 'm.parent_id', 'm.menu_name', 'm.path', 'm.component', 'm.query', 'm.route_name', 'm.visible', 'm.status', 'm.is_frame', 'm.is_cache', 'm.menu_type', 'm.icon', 'm.order_num', 'm.create_time'])
            ->selectRaw("ifnull(m.perms,'') as perms")
            ->leftJoin('sys_role_menu as rm', 'm.menu_id', '=', 'rm.menu_id')
            ->where('rm.role_id', $roleId);
        if ($role['menu_check_strictly']) {
            $notQuery = $this->model->setTable($this->model->getTable() . " as m")->newQuery()
                ->select("m.parent_id")
                ->join('sys_role_menu as rm', 'm.menu_id', '=', 'rm.menu_id')
                ->where('rm.role_id', $roleId);
            $query->whereNotIn('m.menu_id', $notQuery);
        }
        return $query->orderBy("m.parent_id")->orderBy("m.order_num")->get()->toArray();
    }

    public function formatMenuTree($menus)
    {
        $routers = [];
        foreach ($menus as $key => $menu) {
            $router = [
                'hidden' => $menu['visible'] == 0,
                'name' => $this->getRouteName($menu),
                'path' => $this->getRouterPath($menu),
                'component' => $this->getComponent($menu),
                'query' => $menu['query'],
                'meta' => [
                    'title' => $menu['menu_name'],
                    'icon' => $menu['icon'],
                    'noCache' => $menu['is_cache'] == 0,
                    'link' => (stripos($menu['path'], Constants::HTTP) === 0 || stripos($menu['path'], Constants::HTTPS) === 0)? $menu['path'] : '' ,
                ]
            ];
            $cMenus = $menu['children'] ?? [];
            if (!empty($cMenus) && $menu['menu_type'] == Constants::TYPE_DIR) {
                $router['alwaysShow'] = true;
                $router['redirect'] = 'noRedirect';
                $router['children'] = $this->formatMenuTree($cMenus);
            } elseif ($this->isMenuFrame($menu)) {
                $router['meta'] = [];
                $children = [
                    'path' => $menu['path'],
                    'component' => $menu['component'],
                    'name' => $this->getRouteNameWithPath($menu['route_name'], $menu['path']),
                    'meta' => [
                        'title' => $menu['menu_name'],
                        'icon' => $menu['icon'],
                        'noCache' => $menu['is_cache'] == 0,
                        'link' => (stripos($menu['path'], Constants::HTTP) === 0 || stripos($menu['path'], Constants::HTTPS) === 0)? $menu['path'] : '' ,
                    ],
                    'query' => $menu['query'],
                ];
                $childrenList[] = $children;
                $router['children'] = $childrenList;
            } elseif ($menu['parent_id'] == 0 && $this->isInnerLink($menu)) {
                $router['meta'] = [
                    'title' => $menu['menu_name'],
                    'icon' => $menu['icon'],
                ];
                $router['path'] = "/";
                $routerPath = $this->innerLinkReplaceEach($menu['path']);
                $children = [
                    'path' => $routerPath,
                    'component' => Constants::INNER_LINK,
                    'name' => $this->getRouteNameWithPath($menu['route_name'], $routerPath),
                    'meta' => [
                        'title' => $menu['menu_name'],
                        'icon' => $menu['icon'],
                        'link' => $menu['path'],
                    ],
                ];
                $childrenList[] = $children;
                $router['children'] = $childrenList;
            }
            $routers[] = $router;
        }
        return $routers;
    }

    public function getRouteName($menu)
    {
        if ($this->isMenuFrame($menu)) {
            return "";
        }
        return $this->getRouteNameWithPath($menu['route_name'], $menu['path']);
    }
    public function getRouterPath($menu)
    {
        $routerPath = $menu['path'];
        if ($menu['parent_id'] != 0 && $this->isInnerLink($menu)) {
            $routerPath = $this->innerLinkReplaceEach($routerPath);
        }
        if ($menu['parent_id'] == 0 && $menu['menu_type'] == Constants::TYPE_DIR && $menu['is_frame'] == Constants::NO_FRAME) {
            $routerPath = "/" . $menu['path'];
        } elseif ($this->isMenuFrame($menu)) {
            $routerPath = "/";
        }
        return $routerPath;
    }
    public function getComponent($menu)
    {
        $component = Constants::LAYOUT;
        if (!empty($menu['component']) && !$this->isMenuFrame($menu)) {
            $component = $menu['component'];
        } elseif (empty($menu['component']) && $menu['parent_id'] != 0 && $this->isInnerLink($menu)) {
            $component = Constants::INNER_LINK;
        } elseif (empty($menu['component']) && $this->isParentView($menu)) {
            $component = Constants::PARENT_VIEW;
        }
        return $component;
    }
    public function isMenuFrame($menu)
    {
        return $menu['parent_id'] == 0
            && $menu['menu_type'] == Constants::TYPE_MENU
            && $menu['is_frame'] == Constants::NO_FRAME;
    }
    public function getRouteNameWithPath($routeName, $path)
    {
        $routerName = !empty($routeName)? $routeName : $path ;
        return Convert::camelize($routerName, '-');
    }
    public function isInnerLink($menu)
    {
        return $menu['is_frame'] == Constants::NO_FRAME && (stripos($menu['path'], Constants::HTTP) === 0 || stripos($menu['path'], Constants::HTTPS) === 0);
    }
    public function isParentView($menu)
    {
        return $menu['parent_id'] != 0 && $menu['menu_type'] == Constants::TYPE_DIR;
    }
    public function innerLinkReplaceEach($path)
    {
        $search = array(Constants::HTTP, Constants::HTTPS, Constants::WWW, ".", ":");
        $replace = array("", "", "", "/", "/");
        return str_replace($search, $replace, $path);
    }
    public function menusMerge($menu1, $menu2)
    {
        $menuIds = array_column($menu1, 'menu_id');
        foreach ($menu2 as $menu) {
            if (!in_array($menu['menu_id'], $menuIds)) {
                array_push($menu1, $menu);
            }
        }
        return $menu1;
    }


}