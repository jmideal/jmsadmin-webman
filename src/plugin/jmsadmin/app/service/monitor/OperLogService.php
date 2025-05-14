<?php

namespace plugin\jmsadmin\app\service\monitor;

use plugin\jmsadmin\app\model\monitor\OperLogModel;
use plugin\jmsadmin\app\service\system\MenuService;
use plugin\jmsadmin\app\service\system\UserService;
use plugin\jmsadmin\basic\BasicService;
use ReflectionClass;

class OperLogService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new OperLogModel();
        parent::__construct($validate);
    }

    public function getList($params)
    {
        $userName = $params['user_name'] ?? '';
        $userService = new UserService();
        if (!empty($userName)) {
            $userId = $userService->model::withTrashed()->where('user_name', 'like', '%'.$userName.'%')->pluck('user_id')->toArray();
            unset($params['user_name']);
            $params['user_id'] = $userId;
        }
        $ret = parent::getList($params);
        $rows = $ret['rows'] ?? [];
        $userId = array_unique(array_column($rows, 'user_id'));

        $userList = $userService->model::withTrashed()->whereIn('user_id', $userId)->pluck('user_name', 'user_id')->toArray();
        foreach ($rows as $key => $row) {
            $rows[$key]['user_name'] = $userList[$row['user_id']] ?? $row['user_id'];
        }
        $ret['rows'] = $rows;
        return $ret;
    }

    public function logInsert($status, $message, $result = '')
    {
        $request = Request();
        if (strtolower($request->method()) == 'get') {
            return true;
        }
        if (empty($request->controller) || empty($request->action)) {
            return true;
        }
        $reflection = new ReflectionClass($request->controller);
        if (getControllerBaseName($reflection->getShortName()) == 'auth' && in_array($request->action, ['login', 'logout'])) {
            return true;
        }
        list($className, $functionName, $controller, $action, $withResult, $withParams) = getAnnotationLogInfo($reflection);
        if (empty($functionName)) {
            $reqInfo = resolveRequest();
            $menuService = new MenuService();
            if (!empty($reqInfo['permission'])) {
                $menu = $menuService->buildQuery(['perms' => $reqInfo['permission']])->first();
                $functionName = $menu? $menu->menu_name : '' ;
            }
            if (empty($functionName)) {
                $annotationPermission = getAnnotationPermission($reflection);
                if (!empty($annotationPermission)) {
                    $menu = $menuService->buildQuery(['perms' => $annotationPermission])->first();
                    $functionName = $menu? $menu->menu_name : '' ;
                }
            }
        }

        $data = ['controller_name' => $className ?: $controller , 'action_name' => $functionName ?: $action ];
        $adminInfo = adminInfo();
        $data['accept_action'] = $request->controller . '@' . $request->action;
        $data['method'] = $request->method();
        $data['operator_type'] = 1;
        $data['user_id'] = $adminInfo['user_id'] ?? 0;
        $data['oper_url'] = $request->uri();
        $data['oper_ip'] = $request->getRealIp(true);
        if ($withParams) {
            $data['oper_param'] = json_encode($request->all(), JSON_UNESCAPED_UNICODE);
        }
        if ($withResult) {
            if (is_array($result) || is_object($result)) {
                $data['json_result'] = json_encode($result, JSON_UNESCAPED_UNICODE);
            } else {
                $data['json_result'] = $result;
            }
        }
        $data['status'] = $status;
        $data['error_msg'] = $message;
        $data['oper_time'] = date('Y-m-d H:i:s');
        if (!empty($request->__startTime)) {
            $data['cost_time'] = intval((microtime(true) - $request->__startTime) * 1000);
        } else {
            $data['cost_time'] = 0;
        }
        return parent::insert($data);
    }

    public function allRemove()
    {
        return $this->model->newQuery()->delete();
    }
}