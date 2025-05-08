<?php

namespace plugin\jmsadmin\app\validate\system;

use plugin\jmsadmin\basic\BasicValidate;

/**
 * 参数配置Validate
 * @author JM Code Generator
 */
class ConfigValidate extends BasicValidate
{

    /**
     * 接受用户输入的字段
     * @var string[]
     */
    protected $field = [
        'configId' => '参数主键',
        'configName' => '参数名称',
        'configKey' => '参数键名',
        'configValue' => '参数键值',
        'configType' => '系统内置',
        'remark' => '备注',
    ];

    /**
     * 需要验证字段的通用验证规则
     * @var string[]
     */
    protected $rule = [
        'configId'   => 'integer',
        'configName'   => 'max:100',
        'configKey'   => 'max:100',
        'configValue'   => 'max:500',
        'configType'   => 'max:1',
        'remark'   => 'max:500',
    ];

    /**
     * add场景规则
     * @return array
     */
    public function sceneRuleAdd()
    {
        return [
            //接收用户输入字段列表
            'field'   => ['configName', 'configKey', 'configValue', 'configType', 'remark'],
            //必填字段列表
            'required' => ['configName', 'configKey', 'configValue'],
            //场景专属验证规则列表
            'rule'    => []
        ];
    }

    /**
     * edit场景规则
     * @return array
     */
    public function sceneRuleEdit()
    {
        return [
            //接收用户输入字段列表
            'field'   => ['configId', 'configName', 'configKey', 'configValue', 'configType', 'remark'],
            //必填字段列表
            'required' => ['configId', 'configName', 'configKey', 'configValue'],
            //场景专属验证规则列表
            'rule'    => []
        ];
    }

    /**
     * search场景规则
     * @return array
     */
    public function sceneRuleSearch()
    {
        return [
            //接收用户输入字段列表
            'field'   => ['configName', 'configKey', 'configValue', 'configType', 'createTime'],
            //必填字段列表
            'required' => [],
            //场景专属验证规则列表
            'rule'    => ['createTime' => 'date_between', ]
        ];
    }
}
