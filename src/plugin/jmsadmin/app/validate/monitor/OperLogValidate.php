<?php

namespace plugin\jmsadmin\app\validate\monitor;

use plugin\jmsadmin\basic\BasicValidate;

/**
 * 操作日志记录Validate
 * @author JM Code Generator
 */
class OperLogValidate extends BasicValidate
{

    /**
     * 接受用户输入的字段
     * @var string[]
     */
    protected $field = [
        'operId' => '日志主键',
        'moduleName' => '模块名称',
        'controllerName' => '功能名称',
        'actionName' => '操作名称',
        'method' => '请求方式',
        'operatorType' => '操作类别',
        'userId' => '操作人员',
        'operUrl' => '请求URL',
        'operIp' => '主机地址',
        'operLocation' => '操作地点',
        'operParam' => '请求参数',
        'jsonResult' => '返回参数',
        'status' => '操作状态',
        'errorMsg' => '错误消息',
        'operTime' => '操作时间',
        'costTime' => '消耗时间',
    ];

    /**
     * 通用验证规则
     * @var string[]
     */
    protected $rule = [
        'operId'   => 'integer',
        'moduleName'   => 'max:50',
        'controllerName'   => 'max:50',
        'actionName'   => 'max:200',
        'method'   => 'max:10',
        'operatorType'   => 'integer',
        'userId'   => 'integer',
        'operUrl'   => 'max:255',
        'operIp'   => 'max:128',
        'operLocation'   => 'max:255',
        'operParam'   => 'max:2000',
        'jsonResult'   => 'max:2000',
        'status'   => 'in:1,0',
        'errorMsg'   => 'max:2000',
        'operTime'   => 'date',
        'costTime'   => 'integer',
    ];

    /**
     * search场景规则
     * @return array
     */
    public function sceneRuleSearch()
    {
        return [
            //接收用户输入字段列表
            'field'   => ['moduleName', 'controllerName', 'actionName', 'userName', 'operIp', 'status', 'operTime'],
            //必填字段列表
            'required' => [],
            //场景专属验证规则列表
            'rule'    => ['operTime' => 'date_between', ]
        ];
    }
}
