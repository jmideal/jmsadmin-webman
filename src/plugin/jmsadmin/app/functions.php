<?php

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\annotation\UsePermission;
use plugin\jmsadmin\app\model\system\DeptModel;
use plugin\jmsadmin\app\service\AuthService;
use plugin\jmsadmin\constant\Constants;
use plugin\jmsadmin\utils\Util;
use plugin\jmsadmin\utils\Validator;

/**
 * Here is your custom functions.
 */


function userIsRoot($user): bool
{
    return !empty($user['user_id']) && $user['user_id'] == 1;
}
function userIsRootRole($role): bool
{
    return !empty($role['role_id']) && $role['role_id'] == 1;
}

function adminIsSuperScope($adminInfo = null)
{
    $adminInfo = !is_null($adminInfo) ? $adminInfo : adminInfo();
    if (userIsRoot($adminInfo)) {
        return true;
    }
    $roles = $adminInfo['roles'] ?? [];
    foreach ($roles as $role) {
        if (userIsRootRole($role)) {
            return true;
        }
    }
    return false;
}
if (!function_exists('adminInfo')) {
    function adminInfo(): array
    {
        //先从Request对象获取，没有再查redis
        $adminInfo = Request()->adminInfo ?? [];
        if (empty($adminInfo)) {
            $authService = new AuthService();
            $adminInfo = $authService->getLoginUser();
        }
        return $adminInfo;
    }
}
if (!function_exists('verifyIntArray')) {
    function verifyIntArray($array)
    {
        return count($array) === count(array_filter($array, function ($value) {
                return is_numeric($value);
            }));
    }
}
if (!function_exists('matchEnd')) {
    function matchEnd($string, $search)
    {
        $bool = false;
        $search = !is_array($search) ? [$search] : $search;
        foreach ($search as $item) {
            $len = mb_strlen($item);
            if (mb_substr($string, -$len) == $item) {
                return true;
            }
        }
        return $bool;
    }
}
if (!function_exists('ABC2decimal')) {
    function ABC2decimal($abc)
    {
        $ten = 0;
        $len = strlen($abc);
        for ($i = 1; $i <= $len; $i++) {
            $char = substr($abc, 0 - $i, 1);//反向获取单个字符

            $int = ord($char);
            $ten += ($int - 65) * pow(26, $i - 1);
        }
        return $ten;
    }
}
function reverseNumberFormat($formattedNumber) {
    // 移除千位分隔符
    $numberWithoutCommas = str_replace(',', '', $formattedNumber);
    // 转换为浮点数
    $number = (float) $numberWithoutCommas;
    return $number;
}
if (!function_exists('resolveRequest')) {
    function resolveRequest()
    {
        $request = Request();
        $controller = $request->controller;
        $action = $request->action;
        if (empty($controller) || empty($action)) {
            return ["module" => '', "controller" => '', "action" => '', "permission" => '', "plugin" => ''];
        }
        $plugin = $request->plugin;

        $path = $request->path();
        if (empty($plugin)) {
            $reqUri = str_replace("/app/", "", $path);
        } else {
            $reqUri = str_replace("/app/" . $plugin . "/", "", $path);
        }

        if (substr($reqUri, -1) == "/") {
            $reqUri = substr($reqUri, 0, -1);
        }
        if (substr($reqUri, 0, 1) == "/") {
            $reqUri = substr($reqUri, 1);
        }
        $permission = str_replace("/", ":", $reqUri);
        $req = explode(":", $permission);
        $module = '';
        $controller = '';
        if (count($req) < 2) {

        } elseif (count($req) == 2) {
            $controller = $req[0];
        } elseif (count($req) == 3) {
            $module = $req[0];
            $controller = $req[1];
        } else {
            $module = $req[0];
            $tmp = $req;
            unset($tmp[0], $tmp[count($req) - 1]);
            $controller = implode("/", $tmp);
        }
        return compact("module", "controller", "action", "permission", "plugin");
    }
}
function getAnnotationPermission(ReflectionClass $reflection = null)
{
    $request = Request();
    $usePermission = [];
    if (empty($reflection) && !empty($request->controller)) {
        $reflection = new ReflectionClass($request->controller);
    }
    if (!empty($reflection)) {
        $attributes = $reflection->getAttributes(UsePermission::class);
        foreach ($attributes as $attribute) {
            $value = $attribute->newInstance()->value;
            if (!empty($value)) {
                $usePermission[] = $value;
            }
        }
        $reflectionMethod = $reflection->getMethod($request->action);
        $attributes = $reflectionMethod->getAttributes(UsePermission::class);
        foreach ($attributes as $attribute) {
            $value = $attribute->newInstance()->value;
            if (!empty($value)) {
                $usePermission[] = $value;
            }
        }
    }
    return $usePermission;
}

function getAnnotationLogInfo(ReflectionClass $reflection = null)
{
    $request = Request();
    $logInfo = ['className' => '', 'functionName' => '', 'controller' => '', 'action' => '', 'withResult' => true, 'withParams' => true];
    if (empty($reflection) && !empty($request->controller)) {
        $reflection = new ReflectionClass($request->controller);
    }
    if (!empty($reflection)) {
        $logInfo['controller'] = getControllerBaseName($reflection->getShortName());
        $logInfo['action'] = $request->action;
        $attributes = $reflection->getAttributes(LogInfo::class);
        if (!empty($attributes[0])) {
            $logInfo['className'] = $attributes[0]->newInstance()->name;
            $logInfo['withResult'] = $attributes[0]->newInstance()->withResult;
            $logInfo['withParams'] = $attributes[0]->newInstance()->withParams;
        }
        $reflectionMethod = $reflection->getMethod($request->action);
        $attributes = $reflectionMethod->getAttributes(LogInfo::class);
        if (!empty($attributes[0])) {
            $logInfo['functionName'] = $attributes[0]->newInstance()->name;
            $logInfo['withResult'] = $attributes[0]->newInstance()->withResult;
            $logInfo['withParams'] = $attributes[0]->newInstance()->withParams;
        }
    }
    return array_values($logInfo);
}

function getControllerBaseName($controllerName)
{
    $suffix = config('plugin.jmsadmin.app.controller_suffix');
    if (empty($suffix)) {
        return $controllerName;
    }
    if (mb_substr($controllerName, -strlen($suffix)) == $suffix) {
        return lcfirst(mb_substr($controllerName, 0, -strlen($suffix)));
    }
    return $controllerName;
}

function getDeptDataScope($withCurrDeptId = false)
{
    $deptScope = [];
    $adminInfo = adminInfo();
    if (empty($adminInfo['dept']['status'])) {
        return [];
    }
    $userId = $adminInfo['user_id'];
    $dept = $adminInfo['dept'];
    $query = Util::getDb()->table("sys_dept");
    if (!empty(DeptModel::DELETED_AT)) {
        $query->whereNull(DeptModel::DELETED_AT);
    }
    $deptAll = $query->pluck('dept_id')->toArray();
    array_push($deptAll, 0);

    $roles = $adminInfo['roles'];
    $customRoleIds = [];
    foreach ($roles as $key => $role) {
        if ($role['status'] != 1) {
            continue;
        }
        if (userIsRootRole($role)) {
            return $deptAll;
        }
        if ($role['data_scope'] == Constants::DATA_SCOPE_ALL) {
            return $deptAll;
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
    if ($withCurrDeptId) {
        array_push($deptScope, $dept['dept_id']);
    }
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
    return array_unique($deptScope);
}

function getTableInfo($table)
{
    $cacheKey = Constants::TABLE_INFO_KEY . $table;
    $value = Util::getRedis()->get($cacheKey);
    if (!empty($value)) {
        return unserialize($value);
    }
    $value = Util::getDb()->select("desc " . $table);
    if (!empty($value)) {
        $dataCacheExpire = config('plugin.jmsadmin.app.data_cache_expire');
        Util::getRedis()->setex($cacheKey, $dataCacheExpire, serialize($value));
        return $value;
    }
    return [];
}

function getValidateClassByServiceClass($serviceClass)
{
    $namespacePrefix = 'plugin\\jmsadmin\\app\\';
    $serviceNamespacePrefix = $namespacePrefix . 'service';
    $serviceClassSuffix = 'Service';
    $validateNamespacePrefix = $namespacePrefix . 'validate';
    $validateClassSuffix = 'Validate';

    $tmp = explode('\\', $serviceClass);
    $serviceClassName = end($tmp);

    $module = substr($serviceClass, 0, -strlen($serviceClassName));
    $module = str_replace($serviceNamespacePrefix, '', $module);

    if (substr($serviceClassName, -strlen($serviceClassSuffix)) == $serviceClassSuffix) {
        $serviceClassBaseName = substr($serviceClassName, 0, -strlen($serviceClassSuffix));
    } else {
        $serviceClassBaseName = $serviceClassName;
    }

    $validateClass = $validateNamespacePrefix . $module . $serviceClassBaseName . $validateClassSuffix;
    return $validateClass;
}

if (!function_exists('validator')) {
    /**
     * illuminate/validation 验证器
     * @param array $data
     * @param array $rules
     * @param array $customMessages
     * @param array $customAttributes
     * @return \Illuminate\Validation\Factory|\Illuminate\Validation\Validator
     */
    function validator(array $data = [], array $rules = [], array $customMessages = [], array $customAttributes = [])
    {
        $validator = Validator::getInstance();
        if (func_num_args() === 0) {
            return $validator;
        }
        return $validator->make($data, $rules, $customMessages, $customAttributes);
    }
}

if (!function_exists('validatorSingle')) {
    /**
     * illuminate/validation 单一验证器
     * @param mixed $value
     * @param string $rule
     * @return bool
     */
    function validatorSingle(mixed $value,string $rule): bool
    {
        $validator = Validator::getInstance();
        $random = mt_rand(1000, 9999);
        $data[$random] = $value;
        $rules[$random] = $rule;
        $validator = $validator->make($data, $rules);
        return !$validator->fails();
    }
}