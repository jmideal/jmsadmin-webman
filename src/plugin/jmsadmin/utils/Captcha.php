<?php

namespace plugin\jmsadmin\utils;

use plugin\jmsadmin\constant\Constants;
use Webman\Captcha\CaptchaBuilder;
use Webman\Captcha\PhraseBuilder;

class Captcha
{
    public function charCaptcha($uuid):CaptchaBuilder
    {
        $captchaKey = Constants::CAPTCHA_CODE_KEY . $uuid;
        $phraseBuilder = new PhraseBuilder(4, "abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ");
        $phrase = $phraseBuilder->build();
        $captcha = new CaptchaBuilder($phrase);
        $captcha->build(160, 60);
        $captchaExpirationSeconds = config('plugin.jmsadmin.app.captcha_expiration_seconds');
        Util::getRedis()->setex($captchaKey, $captchaExpirationSeconds, strtolower($phrase));
        return $captcha;
    }

    public function validateCaptcha($code, $uuid):bool
    {
        $captchaKey = Constants::CAPTCHA_CODE_KEY . $uuid;
        $value = Util::getRedis()->get($captchaKey);
        Util::getRedis()->del($captchaKey);
        if (empty($value)) {
            return false;
        }
        $code = strtolower($code);
        $value = strtolower($value);
        return hash_equals($code, $value);
    }
}