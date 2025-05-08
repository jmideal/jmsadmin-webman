<?php

namespace plugin\jmsadmin\app\validate\system;

use plugin\jmsadmin\basic\BasicValidate;

/**
 * 字典类型Validate
 * @author JM Code Generator
 */
class DictTypeValidate extends BasicValidate
{

    /**
     * 接受用户输入的字段
     * @var string[]
     */
    protected $field = [
        'dictId' => '字典主键',
        'dictName' => '字典名称',
        'dictType' => '字典类型',
        'status' => '状态',
        'remark' => '备注',
    ];

    /**
     * 需要验证字段的通用验证规则
     * @var string[]
     */
    protected $rule = [
        'dictId'   => 'integer',
        'dictName'   => 'max:100',
        'dictType'   => 'max:100',
        'status'   => 'in:1,0',
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
            'field'   => ['dictName', 'dictType', 'status', 'remark'],
            //必填字段列表
            'required' => ['dictName', 'dictType', 'status'],
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
            'field'   => ['dictId', 'dictName', 'dictType', 'status', 'remark'],
            //必填字段列表
            'required' => ['dictId', 'dictName', 'dictType', 'status'],
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
            'field'   => ['dictName', 'dictType', 'status', 'createTime'],
            //必填字段列表
            'required' => [],
            //场景专属验证规则列表
            'rule'    => ['createTime' => 'date_between', ]
        ];
    }
}
