<?php

namespace plugin\jmsadmin\app\service\system;

use plugin\jmsadmin\app\model\system\PostModel;
use plugin\jmsadmin\basic\BasicService;

class PostService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new PostModel();
        parent::__construct($validate);
    }

}