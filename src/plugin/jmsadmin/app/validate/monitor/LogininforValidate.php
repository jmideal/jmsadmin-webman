<?php

namespace plugin\jmsadmin\app\validate\monitor;

use plugin\jmsadmin\basic\BasicValidate;

/**
 * 系统访问记录Validate
 * @author JM Code Generator
 */
class LogininforValidate extends BasicValidate
{

    /**
     * 接受用户输入的字段
     * @var string[]
     */
    protected $field = [
        'infoId' => '访问ID',
        'userName' => '用户账号',
        'ipaddr' => '登录IP地址',
        'loginLocation' => '登录地点',
        'browser' => '浏览器类型',
        'os' => '操作系统',
        'status' => '登录状态',
        'msg' => '提示消息',
        'loginTime' => '访问时间',
    ];

    /**
     * 通用验证规则
     * @var string[]
     */
    protected $rule = [
        'infoId'   => 'integer',
        'userName'   => 'max:50',
        'ipaddr'   => 'max:128',
        'loginLocation'   => 'max:255',
        'browser'   => 'max:50',
        'os'   => 'max:50',
        'status'   => 'in:1,0',
        'msg'   => 'max:255',
        'loginTime'   => 'date',
    ];

    /**
     * search场景规则
     * @return array
     */
    public function sceneRuleSearch()
    {
        return [
            //接收用户输入字段列表
            'field'   => ['userName', 'ipaddr', 'status', 'loginTime'],
            //必填字段列表
            'required' => [],
            //场景专属验证规则列表
            'rule'    => ['loginTime' => 'date_between', ]
        ];
    }
}
