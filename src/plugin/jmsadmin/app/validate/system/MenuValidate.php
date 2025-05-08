<?php

namespace plugin\jmsadmin\app\validate\system;

use plugin\jmsadmin\basic\BasicValidate;

class MenuValidate extends BasicValidate
{
    protected $field = [
        'menuId' => '菜单ID',
        'menuName' => '菜单名称',
        'parentId' => '父菜单ID',
        'orderNum' => '显示顺序',
        'path' => '路由地址',
        'component' => '组件路径',
        'query' => '路由参数',
        'routeName' => '路由名称',
        'isFrame' => '是否为外链',
        'isCache' => '是否缓存',
        'menuType' => '菜单类型',
        'visible' => '显示状态',
        'status' => '菜单状态',
        'perms' => '权限标识',
        'icon' => '菜单图标',
        'remark' => '备注',
    ];

    protected $rule = [
        'menuId'   => 'integer',
        'menuName' => 'max:50|min:2',
        'parentId' => 'integer',
        'orderNum' => 'integer',
        'isFrame'  => 'in:0,1',
        'isCache'  => 'in:0,1',
        'menuType' => 'in:M,C,F',
        'visible'  => 'in:0,1',
        'status'   => 'in:0,1',
    ];

    /**
     * add场景规则
     * @return array
     */
    public function sceneRuleAdd()
    {
        return [
            //接收用户输入字段列表
            'field'   => array_keys($this->field),
            //必填字段列表
            'required' => ['menuName', 'parentId', 'menuType'],
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
            'field'   => array_keys($this->field),
            //必填字段列表
            'required' => ['menuId', 'menuName', 'parentId', 'menuType'],
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
            'field'   => ['menuName', 'status'],
            //必填字段列表
            'required' => [],
            //场景专属验证规则列表
            'rule'    => []
        ];
    }
}