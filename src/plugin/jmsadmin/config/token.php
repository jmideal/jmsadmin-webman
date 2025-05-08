<?php
return  [
    'jwt' => [
        //加密方式
        'algorithm'    => 'HS512',
        //密钥
        'token_secret' => getenv('TOKEN_SECRET'),
        //过期时间
        'token_expire' => getenv('TOKEN_EXPIRE'),
        //刷新时间
        'token_refresh' => getenv('TOKEN_REFRESH'),
        //token http头
        'token_header' => 'Authorization',
        //token头
        'token_prefix' => 'Bearer ',
    ]
];
