<?php

namespace plugin\jmsadmin\app\middleware;

use plugin\jmsadmin\app\service\AuthService;
use plugin\jmsadmin\app\service\system\ConfigService;
use plugin\jmsadmin\utils\ApiResult;
use plugin\jmsadmin\utils\Convert;
use ReflectionClass;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class AuthControl implements MiddlewareInterface
{
    public function process(Request $request, callable $handler) : Response
    {
        $authService = new AuthService();
        $user = $authService->getLoginUser();

        // 通过反射获取控制器哪些方法不需要登录
        $reflection = new ReflectionClass($request->controller);
        $noNeedLogin = $reflection->getDefaultProperties()['noNeedLogin'] ?? [];
        //是否需要判断演示模式
        if (getControllerBaseName($reflection->getShortName()) != 'auth' && !in_array($request->action, $noNeedLogin)) {
            $method = strtolower($request->method());
            $action = strtolower($request->action);
            $postMethodSuffix = ['add', 'insert', 'edit', 'update', 'change', 'remove', 'delete', 'import', 'export', 'generate', 'refresh', 'flush', 'upload'];
            $needPost = false;
            foreach ($postMethodSuffix as $suffix) {
                if (mb_substr($action, -mb_strlen($suffix)) == $suffix) {
                    $needPost = true;
                    break;
                }
            }
            if ($needPost) {
                if ($method != 'post') {
                    return ApiResult::error("该接口需要post方式请求", 500);
                }
                $__timestamp = $request->post('__timestamp');
                if (empty($__timestamp) || !is_numeric($__timestamp)) {
                    return ApiResult::error("请求需要携带有效的参数__timestamp，请重试", 500);
                }
                $__timestamp = (int)ceil($__timestamp / 1000);
                if (abs($__timestamp - time()) > 10) {
                    return ApiResult::error("请求已过期，请重试", 500);
                }
                $configService = new ConfigService();
                $demoMode = $configService->getKeyInfo('sys.demo.mode');
                $demoMode = Convert::toBool($demoMode, true);
                if ($demoMode) {
                    return ApiResult::error("当前处于演示模式，不支持该操作", 500);
                }
            }
        }

        // 通过反射获取控制器哪些方法不需要鉴权
        $noNeedAuth = $reflection->getDefaultProperties()['noNeedAuth'] ?? [];
        $noNeedAuth = array_merge($noNeedLogin, $noNeedAuth);

        // 访问的方法需要鉴权
        if (!in_array('*', $noNeedAuth) && !in_array($request->action, $noNeedAuth)) {
            //用户未登录
            if (empty($user)) {
                return ApiResult::error("未登录或登录已过期，请重新登录", 401);
            }
            $resolveRequest = resolveRequest();
            $permission = $resolveRequest['permission'];
            $annotationPermission = getAnnotationPermission();
            if (empty($permission) && empty($annotationPermission)) {
                return ApiResult::error("无权访问该资源，请联系管理员", 403);
            }
            $havePermissions = $authService->getMenuPermission($user);
            if (empty($havePermissions)) {
                return ApiResult::error("无权访问该资源，请联系管理员", 403);
            }
            if (!in_array("*:*:*", $havePermissions) && !in_array($permission, $havePermissions) && empty(array_intersect($annotationPermission, $havePermissions))) {
                return ApiResult::error("无权访问该资源，请联系管理员", 403);
            }
        }

        // 不需要鉴权，请求继续向洋葱芯穿越
        return $handler($request);
    }
}