<?php

namespace plugin\jmsadmin\app\service\monitor;

use foroco\BrowserDetection;
use plugin\jmsadmin\app\model\monitor\LogininforModel;
use plugin\jmsadmin\basic\BasicService;
use plugin\jmsadmin\constant\Constants;
use plugin\jmsadmin\utils\Util;

class LogininforService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new LogininforModel();
        parent::__construct($validate);
    }

    public function logInsert($userName, $status, $msg)
    {
        $params = ['user_name' => $userName, 'status' => $status == 1 ? '1' : '0' , 'msg' => $msg];
        $params['ipaddr'] = Request()->getRealIp(true);
        /*if (preg_match('/^(10\.|172\.(1[6-9]|2[0-9]|3[0-1])\.|192\.168\.|127\.)/', $params['ipaddr'])) {
            $params['loginLocation'] = "局域网";
        }*/
        $params['login_location'] = '';
        $Browser = new BrowserDetection();
        $result = $Browser->getAll(Request()->header('user-agent'));
        $params['browser'] = $result['browser_title'];
        $params['os'] = $result['os_title'];
        $params['login_time'] = date('Y-m-d H:i:s');
        return $this->insert($params);
    }

    public function allRemove()
    {
        return $this->model->newQuery()->delete();
    }

    public function unLockUserCache($userName)
    {
        $userName = is_array($userName) ? $userName : [$userName];
        foreach ($userName as $k => $v) {
            if (!empty($v)) {
                $cacheKey = Constants::USER_PWD_ERR_KEY . $v;
                if (Util::getRedis()->exists($cacheKey)) {
                    Util::getRedis()->del($cacheKey);
                }
            }
        }
        return true;
    }
}