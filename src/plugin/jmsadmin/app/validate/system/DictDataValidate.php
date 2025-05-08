<?php

namespace plugin\jmsadmin\app\validate\system;

use plugin\jmsadmin\basic\BasicValidate;

/**
 * 字典数据Validate
 * @author JM Code Generator
 */
class DictDataValidate extends BasicValidate
{

    /**
     * 接受用户输入的字段
     * @var string[]
     */
    protected $field = [
        'dictCode' => '字典编码',
        'dictSort' => '字典排序',
        'dictLabel' => '字典标签',
        'dictValue' => '字典键值',
        'dictType' => '字典类型',
        'cssClass' => '样式属性',
        'listClass' => '表格回显样式',
        'isDefault' => '是否默认',
        'status' => '状态',
        'remark' => '备注',
    ];

    /**
     * 需要验证字段的通用验证规则
     * @var string[]
     */
    protected $rule = [
        'dictCode'   => 'integer',
        'dictSort'   => 'integer',
        'dictLabel'   => 'max:100',
        'dictValue'   => 'max:100',
        'dictType'   => 'max:100',
        'cssClass'   => 'max:100',
        'listClass'   => 'max:100',
        'isDefault'   => 'in:Y,N',
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
            'field'   => ['dictSort', 'dictLabel', 'dictValue', 'dictType', 'cssClass', 'listClass', 'status', 'remark'],
            //必填字段列表
            'required' => ['dictSort', 'dictLabel', 'dictValue', 'dictType', 'status'],
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
            'field'   => ['dictCode', 'dictSort', 'dictLabel', 'dictValue', 'dictType', 'cssClass', 'listClass', 'status', 'remark'],
            //必填字段列表
            'required' => ['dictCode', 'dictSort', 'dictLabel', 'dictValue', 'dictType', 'status'],
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
            'field'   => ['dictLabel', 'dictType', 'status'],
            //必填字段列表
            'required' => [],
            //场景专属验证规则列表
            'rule'    => []
        ];
    }
}
