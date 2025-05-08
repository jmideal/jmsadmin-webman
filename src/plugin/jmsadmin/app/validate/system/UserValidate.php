<?php

namespace plugin\jmsadmin\app\validate\system;

use plugin\jmsadmin\basic\BasicValidate;

/**
 * 用户信息Validate
 * @author JM Code Generator
 */
class UserValidate extends BasicValidate
{

    /**
     * 接受用户输入的字段
     * @var string[]
     */
    protected $field = [
        'userId' => '用户ID',
        'deptId' => '部门ID',
        'userName' => '用户账号',
        'nickName' => '用户昵称',
        'email' => '用户邮箱',
        'phonenumber' => '手机号码',
        'sex' => '用户性别',
        'avatar' => '头像地址',
        'password' => '密码',
        'status' => '帐号状态',
        'createTime' => '创建时间',
        'remark' => '备注',
        'postIds' => '岗位',
        'roleIds' => '角色',
    ];

    /**
     * 通用验证规则
     * @var string[]
     */
    protected $rule = [
        'userId'   => 'integer',
        'deptId'   => 'integer',
        'userName'   => 'max:30',
        'nickName'   => 'max:30',
        'email'   => 'max:50|email',
        'phonenumber'   => 'mobile',
        'sex'   => 'in:0,1,2',
        'avatar'   => 'max:100',
        'password'   => 'max:100',
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
            'field'   => ['deptId', 'userName', 'nickName', 'email', 'phonenumber', 'sex', 'avatar', 'password', 'status', 'postIds', 'roleIds', 'remark'],
            //必填字段列表
            'required' => ['deptId', 'userName', 'password', 'status', 'postIds', 'roleIds'],
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
            'field'   => ['userId', 'deptId', 'userName', 'nickName', 'email', 'phonenumber', 'sex', 'avatar', 'status', 'postIds', 'roleIds', 'remark'],
            //必填字段列表
            'required' => ['userId', 'deptId', 'userName', 'status', 'postIds', 'roleIds'],
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
            'field'   => ['deptId', 'roleId', 'userName', 'phonenumber', 'status', 'createTime'],
            //必填字段列表
            'required' => [],
            //场景专属验证规则列表
            'rule'    => ['createTime' => 'date_between', ]
        ];
    }
}
