<?php

namespace plugin\jmsadmin\app\validate\system;

use plugin\jmsadmin\basic\BasicValidate;

/**
 * 角色信息Validate
 * @author JM Code Generator
 */
class RoleValidate extends BasicValidate
{

    /**
     * 接受用户输入的字段
     * @var string[]
     */
    protected $field = [
        'roleId' => '角色ID',
        'roleName' => '角色名称',
        'roleKey' => '角色权限字符串',
        'roleSort' => '显示顺序',
        'dataScope' => '数据范围',
        'menuCheckStrictly' => '菜单树选择项是否关联显示',
        'deptCheckStrictly' => '部门树选择项是否关联显示',
        'status' => '角色状态',
        'remark' => '备注',
        'menuIds' => '菜单ID',
        'deptIds' => '部门ID',
    ];

    /**
     * 通用验证规则
     * @var string[]
     */
    protected $rule = [
        'roleId'   => 'integer',
        'roleName'   => 'max:30',
        'roleKey'   => 'max:100',
        'roleSort'   => 'integer',
        'dataScope'   => 'max:1',
        'menuCheckStrictly'   => 'boolean',
        'deptCheckStrictly'   => 'boolean',
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
            'field'   => ['roleName', 'roleKey', 'roleSort', 'dataScope', 'menuCheckStrictly', 'deptCheckStrictly', 'status', 'remark', 'menuIds'],
            //必填字段列表
            'required' => ['roleName', 'roleKey', 'roleSort', 'status'],
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
            'field'   => ['roleId', 'roleName', 'roleKey', 'roleSort', 'dataScope', 'menuCheckStrictly', 'deptCheckStrictly', 'status', 'remark', 'menuIds'],
            //必填字段列表
            'required' => ['roleId', 'roleName', 'roleKey', 'roleSort', 'status'],
            //场景专属验证规则列表
            'rule'    => []
        ];
    }

    public function sceneRuleDataScopeEdit()
    {
        return [
            //接收用户输入字段列表
            'field'   => ['roleId', 'dataScope', 'deptCheckStrictly', 'deptIds'],
            //必填字段列表
            'required' => ['roleId', 'dataScope'],
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
            'field'   => ['roleName', 'roleKey', 'status', 'createTime'],
            //必填字段列表
            'required' => [],
            //场景专属验证规则列表
            'rule'    => ['createTime' => 'date_between', ]
        ];
    }
}
