<?php

namespace plugin\jmsadmin\utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use plugin\jmsadmin\constant\Constants;
use plugin\jmsadmin\exception\ApiException;

class Token
{
    private $config;
    public function __construct()
    {
        $this->config = config('plugin.jmsadmin.token.jwt');
    }

    public function getToken()
    {
        $token = request()->header($this->config['token_header'], '');
        if (!empty($token) && strpos($token, $this->config['token_prefix']) === 0) {
            $token = str_replace($this->config['token_prefix'], '', $token);
        } else {
            $token = '';
        }
        return $token;
    }

    public function createToken($payload)
    {
        return JWT::encode($payload, $this->config['token_secret'], $this->config['algorithm']);
    }

    public function parseToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->config['token_secret'], $this->config['algorithm']));
            return (array)$decoded;
        } catch (\Throwable $e) {
            throw new ApiException('请求无效', 401);
        }
    }

    public function getTokenKey($uuid): string
    {
        return Constants::LOGIN_TOKEN_KEY . $uuid;
    }
}