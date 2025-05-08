<?php

namespace plugin\jmsadmin\app\middleware;

use plugin\jmsadmin\app\service\AuthService;
use plugin\jmsadmin\utils\ApiResult;
use plugin\jmsadmin\utils\Random;
use ReflectionClass;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;
class LoginControl implements MiddlewareInterface
{
    public function process(Request $request, callable $handler) : Response
    {
        $authService = new AuthService();
        $user = $authService->getLoginUser();
        if (!empty($user['uuid']) && !empty($user['user_id']) && !empty($user['expire_time'])) {
            //判断是否需要刷新token
            $tokenRefresh = config('plugin.jmsadmin.token.jwt.token_refresh');
            if (strtotime($user['expire_time']) - time() < $tokenRefresh) {
                $authService->refreshLoginUser($user);
            }
            //将用户登录信息寄存在Request对象上
            $request->adminInfo = $user;
            // 已经登录，请求继续向洋葱芯穿越
            return $handler($request);
        }

        // 通过反射获取控制器哪些方法不需要登录
        $controller = new ReflectionClass($request->controller);
        $noNeedLogin = $controller->getDefaultProperties()['noNeedLogin'] ?? [];

        // 访问的方法需要登录
        if (!in_array('*', $noNeedLogin) && !in_array($request->action, $noNeedLogin)) {
            // 拦截请求，返回一个JSON响应，请求停止向洋葱芯穿越
            return ApiResult::error("未登录或登录已过期，请重新登录", 401);
        }

        // 不需要登录，请求继续向洋葱芯穿越
        return $handler($request);
    }

}