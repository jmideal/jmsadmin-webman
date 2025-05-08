<?php

namespace plugin\jmsadmin\app\validate\system;

use plugin\jmsadmin\basic\BasicValidate;

/**
 * 岗位信息Validate
 * @author JM Code Generator
 */
class PostValidate extends BasicValidate
{

    /**
     * 接受用户输入的字段
     * @var string[]
     */
    protected $field = [
        'postId' => '岗位ID',
        'postCode' => '岗位编码',
        'postName' => '岗位名称',
        'postSort' => '显示顺序',
        'status' => '状态',
        'remark' => '备注',
    ];

    /**
     * 需要验证字段的通用验证规则
     * @var string[]
     */
    protected $rule = [
        'postId'   => 'integer',
        'postCode'   => 'max:64',
        'postName'   => 'max:50',
        'postSort'   => 'integer',
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
            'field'   => ['postCode', 'postName', 'postSort', 'status', 'remark'],
            //必填字段列表
            'required' => ['postCode', 'postName', 'postSort', 'status'],
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
            'field'   => ['postId', 'postCode', 'postName', 'postSort', 'status', 'remark'],
            //必填字段列表
            'required' => ['postId', 'postCode', 'postName', 'postSort', 'status'],
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
            'field'   => ['postCode', 'postName', 'status', 'createTime'],
            //必填字段列表
            'required' => [],
            //场景专属验证规则列表
            'rule'    => ['createTime' => 'date_between', ]
        ];
    }
}
