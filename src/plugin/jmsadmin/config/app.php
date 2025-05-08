<?php

return [
    'debug' => getenv('APP_DEBUG') === 'true',
    'controller_suffix' => 'Controller',
    'controller_reuse' => false,
    'version' => '1.0.0',
    'upload_path' => getenv('UPLOAD_PATH'),
    'img_domain' => getenv('IMG_DOMAIN'),
    'data_cache_expire' => 60*60*24*7,
    'captcha_expiration_seconds' => 60*2,
];
