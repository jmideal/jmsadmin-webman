<?php

namespace plugin\jmsadmin\app\service;

use plugin\jmsadmin\app\model\scopes\AccessDataScope;
use plugin\jmsadmin\app\model\system\UserModel;
use plugin\jmsadmin\app\service\monitor\LogininforService;
use plugin\jmsadmin\app\service\system\MenuService;
use plugin\jmsadmin\app\service\system\RoleMenuService;
use plugin\jmsadmin\app\service\system\UserService;
use plugin\jmsadmin\constant\Constants;
use plugin\jmsadmin\exception\ApiException;
use plugin\jmsadmin\utils\Token;
use plugin\jmsadmin\utils\Util;

class AuthService
{
    public function login($username, $password):array
    {
        $logininforService = new LogininforService();
        $user = UserModel::where('user_name', $username)->first();
        if (empty($user)) {
            $logininforService->logInsert($username, 0, '用户不存在');
            throw new ApiException("用户不存在或密码错误");
        }
        if ($user->status != 1) {
            $logininforService->logInsert($username, 0, '用户已停用');
            throw new ApiException("用户已停用");
        }
        $password_hash = $user->password;
        if (!password_verify($password, $password_hash)) {
            $cacheKey = Constants::USER_PWD_ERR_KEY . $username;
            Util::getRedis()->incr($cacheKey);
            if (Util::getRedis()->ttl($cacheKey) == -1) {
                Util::getRedis()->expire($cacheKey, 60*60);
            }
            $logininforService->logInsert($username, 0, '密码错误');
            throw new ApiException("用户不存在或密码错误");
        }
        $user->login_ip = request()->getRealIp();
        $user->login_date = date("Y-m-d H:i:s");
        $user->save();
        $userArray = $user->toArray();
        unset($userArray['password']);
        return $userArray;
    }

    public function getLoginUser():array
    {
        $tokenUtil = new Token();
        $token = $tokenUtil->getToken();
        if (empty($token)) {
            return [];
        }
        $data = $tokenUtil->parseToken($token);
        if (empty($data['uuid'])) {
            return [];
        }
        $cacheKey = $tokenUtil->getTokenKey($data['uuid']);
        $value = Util::getRedis()->get($cacheKey);
        if (!empty($value)) {
            $user = unserialize($value);
            $user = $this->pruneUser($user);
            return $user;
        } else {
            return [];
        }
    }

    public function setLoginUser($user)
    {
        $user = $this->pruneUser($user);
        if (!empty($user['uuid'])) {
            $uuid = $user['uuid'];
            $user['expire_time'] = date("Y-m-d H:i:s", time() + config('plugin.jmsadmin.token.jwt.token_expire'));
            $cacheKey = (new Token())->getTokenKey($uuid);
            Util::getRedis()->setex($cacheKey, config('plugin.jmsadmin.token.jwt.token_expire'), serialize($user));
        }
    }

    public function delLoginUser($user)
    {
        if (!empty($user['uuid'])) {
            $cacheKey = (new Token())->getTokenKey($user['uuid']);
            Util::getRedis()->del($cacheKey);
        }
    }

    public function refreshLoginUser($user)
    {
        $user = $this->pruneUser($user);
        if (!empty($user['uuid'])) {
            $uuid = $user['uuid'];
            $user['expire_time'] = date("Y-m-d H:i:s", time() + config('plugin.jmsadmin.token.jwt.token_expire'));
            $cacheKey = (new Token())->getTokenKey($uuid);
            $exists = Util::getRedis()->exists($cacheKey);
            if ($exists && $user['status'] == 1) {
                Util::getRedis()->setex($cacheKey, config('plugin.jmsadmin.token.jwt.token_expire'), serialize($user));
            }
        }
    }

    public function updatePwd($oldPassword, $newPassword)
    {
        $loginUser = $this->getLoginUser();
        if (empty($loginUser['user_name'])) {
            throw new ApiException("用户不存在或登录已过期");
        }
        $user = UserModel::where('user_name', $loginUser['user_name'])->first();
        if (empty($user)) {
            throw new ApiException("用户不存在或登录已过期");
        }
        if ($user->status != 1) {
            throw new ApiException("用户已停用");
        }
        $password_hash = $user->password;
        if (!password_verify($oldPassword, $password_hash)) {
            throw new ApiException("用户不存在或密码错误");
        }
        $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->save();
    }

    public function getRolePermission($user):array
    {
        if (adminIsSuperScope($user)) {
            return ['admin'];
        } else {
            $roles = $user['roles'] ?? [];
            $roleList = [];
            if (!empty($roles)) {
                foreach ($roles as $role) {
                    $roleList = array_merge($roleList, explode(',', $role['role_key']));
                }
            }
            return $roleList;
        }
    }

    function getMenuPermission($user)
    {
        if (adminIsSuperScope($user)) {
            return ['*:*:*'];
        } else {
            $roles = $user['roles'] ?? [];
            $roleIds = array_column($roles, 'role_id');
            if (empty($roleIds)) {
                return [];
            }
            $roleMenuService = new RoleMenuService();
            $menuIds = $roleMenuService->buildQuery(['role_id' => $roleIds], [])->pluck('menu_id')->toArray();
            if (empty($menuIds)) {
                return [];
            }
            $menuService = new MenuService();
            $perms = $menuService->buildQuery(['menu_id' => $menuIds, 'status' => '1'], [])->pluck('perms')->toArray();
            $perms = array_filter($perms, function ($perm) {
                return !empty($perm);
            });
            return array_values($perms);
        }
    }
    public function pruneUser($user)
    {
        if (isset($user['password'])) {
            unset($user['password']);
        }
        if ($user['status'] != '1') {
            return [];
        }
        if (!empty($user['delete_time'])) {
            return [];
        }
        if (!empty($user['dept']) && $user['dept']['status'] != '1') {
            $user['dept'] = [];
        }
        if (!empty($user['roles'])) {
            foreach ($user['roles'] as $key => $role) {
                if (!empty($role['delete_time']) || $role['status'] != '1') {
                    unset($user['roles'][$key]);
                }
            }
        }
        return $user;
    }

    public function refreshCacheByUserOrRole($userId = [], $roleId = [])
    {
        $userId = is_array($userId) ? $userId : [$userId];
        $roleId = is_array($roleId) ? $roleId : [$roleId];
        $keys = Util::getRedis()->keys(Constants::LOGIN_TOKEN_KEY . "*");
        $userService = new UserService();
        foreach ($keys as $key) {
            $value = Util::getRedis()->get($key);
            if (empty($value)) {
                continue;
            }
            $user = unserialize($value);
            if (empty($user)) {
                continue;
            }
            if (in_array($user['user_id'], $userId)) {
                try {
                    $userInfo = $userService->getInfo($user['user_id']);
                    $userInfo['uuid'] = $user['uuid'];
                    $this->refreshLoginUser($userInfo);
                } catch (\Throwable $e) {
                    continue;
                }
            }
            if (empty($userInfo)) {
                $roles = $user['roles'] ?? [];
                foreach ($roles as $role) {
                    if (in_array($role['role_id'], $roleId)) {
                        try {
                            $userInfo = $userService->getInfo($user['user_id']);
                            $userInfo['uuid'] = $user['uuid'];
                            $this->refreshLoginUser($userInfo);
                            break;
                        } catch (\Throwable $e) {
                            continue;
                        }
                    }
                }
            }
        }
    }
}