<?php

namespace plugin\jmsadmin\app\controller;

use foroco\BrowserDetection;
use plugin\jmsadmin\app\service\AuthService;
use plugin\jmsadmin\app\service\monitor\LogininforService;
use plugin\jmsadmin\app\service\system\ConfigService;
use plugin\jmsadmin\app\service\system\MenuService;
use plugin\jmsadmin\app\service\system\UserService;
use plugin\jmsadmin\constant\Constants;
use plugin\jmsadmin\utils\ApiResult;
use plugin\jmsadmin\utils\Captcha;
use plugin\jmsadmin\utils\Convert;
use plugin\jmsadmin\utils\Random;
use plugin\jmsadmin\utils\Token;
use plugin\jmsadmin\utils\Util;
use support\Request;
use support\Response;
use Webman\RateLimiter\Annotation\RateLimiter;

class AuthController
{
    /**
     * 不需要登录的方法
     */
    protected $noNeedLogin = ['captchaImage', 'login', 'logout'];
    /**
     * 不需要鉴权的方法，中间件会和不需要登录的方法进行合并
     */
    protected $noNeedAuth = ['getInfo', 'getRouters'];

    #[RateLimiter(limit: 50, ttl: 60*60, message: '接口请求次数超限')]
    public function captchaImage(Request $request):Response
    {
        $uuid = Random::uuidShort();
        $image = '';
        $configService = new ConfigService();
        $captchaEnabled = $configService->getKeyInfo('sys.account.captchaEnabled');
        $captchaEnabled = Convert::Boolean($captchaEnabled);
        if ($captchaEnabled) {
            $captcha = new Captcha();
            $charBuilder = $captcha->charCaptcha($uuid);
            $image = $charBuilder->get();
        }
        $data = [
            "captchaEnabled" => $captchaEnabled,
            "uuid" => $uuid,
            "img" => base64_encode($image)
        ];
        return ApiResult::success($data);
    }

    public static function getUserName(): string
    {
        return request()->post('username', '');
    }

    #[RateLimiter(limit: 50, ttl: 60*60, message: '接口请求次数超限')]
    #[RateLimiter(limit: 20, ttl: 60*60, key: [AuthController::class, 'getUserName'], message: '接口请求次数超限')]
    public function login(Request $request):Response
    {
        $code = $request->post('code', '');
        $uuid = $request->post('uuid', '');
        $username = $request->post('username', '');
        $password = $request->post('password', '');
        if (empty($uuid) || empty($username) || empty($password)) {
            return ApiResult::error('登录失败');
        }
        if (!preg_match('/^\w{2,20}$/i', $username)) {
            return ApiResult::error("用户名只能包含字母数字和下划线");
        }
        $cacheKey = Constants::USER_PWD_ERR_KEY . $username;
        $errCount = Util::getRedis()->get($cacheKey);
        if (!empty($errCount) && intval($errCount) >= 5) {
            return ApiResult::error('密码错误次数超限，请休息一会');
        }
        $logininforService = new LogininforService();
        $configService = new ConfigService();
        $captchaEnabled = $configService->getKeyInfo('sys.account.captchaEnabled');
        $captchaEnabled = Convert::Boolean($captchaEnabled);
        if ($captchaEnabled) {
            if (empty($code)) {
                return ApiResult::error('登录失败');
            }
            $captcha = new Captcha();
            if (!$captcha->validateCaptcha($code, $uuid)) {
                $logininforService->logInsert($username, 0, '验证码有误');
                return ApiResult::error('验证码有误');
            }
        }

        $authService = new AuthService();
        $user = $authService->login($username, $password);
        $userService = new UserService();
        $userFull = $userService->getInfo($user['user_id']);
        if (empty($userFull['dept'])) {
            $logininforService->logInsert($username, 0, '用户未设置部门信息');
            return ApiResult::error('未设置部门信息，请联系系统管理员');
        }
        if (empty($userFull['dept']['status'])) {
            $logininforService->logInsert($username, 0, '用户所在部门未启用');
            return ApiResult::error('所在部门未启用，请联系系统管理员');
        }
        $user = array_merge($user, $userFull);
        $uuid = Random::uuid();
        $user['uuid'] = $uuid;
        $token = (new Token())->createToken(['uuid' => $uuid]);

        $Browser = new BrowserDetection();
        $result = $Browser->getAll(Request()->header('user-agent'));
        $user['browser'] = $result['browser_title'];
        $user['os'] = $result['os_title'];

        $authService->setLoginUser($user);
        $logininforService->logInsert($username, 1, '登录成功');
        return ApiResult::success(['token' => $token]);
    }

    public function logout(Request $request):Response
    {
        $authService = new AuthService();
        $user = $authService->getLoginUser();
        if (!empty($user['uuid'])) {
            $authService->delLoginUser($user);
        }
        return ApiResult::success();
    }

    public function getInfo(Request $request):Response
    {
        $authService = new AuthService();
        $user = $authService->getLoginUser();
        $roles = $authService->getRolePermission($user);
        $permissions = $authService->getMenuPermission($user);
        unset($user['password']);
        return ApiResult::success(['user' => $user, 'roles' => $roles, 'permissions' => $permissions]);
    }

    public function getRouters(Request $request):Response
    {
        $authService = new AuthService();
        $menuService = new MenuService();
        $user = $authService->getLoginUser();
        $menuTree = $menuService->selectMenuTreeByUser($user);
        $menuTree = $menuService->formatMenuTree($menuTree);
        return ApiResult::success($menuTree);
    }
}