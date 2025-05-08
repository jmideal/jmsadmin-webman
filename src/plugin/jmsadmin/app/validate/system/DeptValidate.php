<?php

namespace plugin\jmsadmin\app\validate\system;

use plugin\jmsadmin\basic\BasicValidate;

/**
 * 部门Validate
 * @author JM Code Generator
 */
class DeptValidate extends BasicValidate
{

    /**
     * 接受用户输入的字段
     * @var string[]
     */
    protected $field = [
        'deptId' => '部门id',
        'parentId' => '父部门id',
        'ancestors' => '祖级列表',
        'deptName' => '部门名称',
        'orderNum' => '显示顺序',
        'leader' => '负责人',
        'phone' => '联系电话',
        'email' => '邮箱',
        'status' => '部门状态',
    ];

    /**
     * 通用验证规则
     * @var string[]
     */
    protected $rule = [
        'deptId'   => 'integer',
        'parentId'   => 'integer',
        'ancestors'   => 'max:50',
        'deptName'   => 'max:30',
        'orderNum'   => 'integer',
        'leader'   => 'max:20',
        'phone'   => 'max:11',
        'email'   => 'max:50',
        'status'   => 'in:1,0',
    ];

    /**
     * add场景规则
     * @return array
     */
    public function sceneRuleAdd()
    {
        return [
            //接收用户输入字段列表
            'field'   => ['parentId', 'ancestors', 'deptName', 'orderNum', 'leader', 'phone', 'email', 'status'],
            //必填字段列表
            'required' => ['parentId', 'deptName', 'orderNum', 'status'],
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
            'field'   => ['deptId', 'parentId', 'ancestors', 'deptName', 'orderNum', 'leader', 'phone', 'email', 'status'],
            //必填字段列表
            'required' => ['deptId', 'parentId', 'deptName', 'orderNum', 'status'],
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
            'field'   => ['parentId', 'deptName', 'status', 'createTime'],
            //必填字段列表
            'required' => [],
            //场景专属验证规则列表
            'rule'    => ['createTime' => 'date_between', ]
        ];
    }
}
