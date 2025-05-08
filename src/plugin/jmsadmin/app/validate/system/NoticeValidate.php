<?php

namespace plugin\jmsadmin\app\validate\system;

use plugin\jmsadmin\basic\BasicValidate;

/**
 * 通知公告Validate
 * @author JM Code Generator
 */
class NoticeValidate extends BasicValidate
{

    /**
     * 接受用户输入的字段
     * @var string[]
     */
    protected $field = [
        'noticeId' => '公告ID',
        'noticeTitle' => '公告标题',
        'noticeType' => '公告类型',
        'noticeContent' => '公告内容',
        'status' => '公告状态',
        'remark' => '备注',
    ];

    /**
     * 通用验证规则
     * @var string[]
     */
    protected $rule = [
        'noticeId'   => 'integer',
        'noticeTitle'   => 'max:50',
        'noticeType'   => 'max:1',
        'status'   => 'in:1,0',
        'remark'   => 'max:255',
    ];

    /**
     * add场景规则
     * @return array
     */
    public function sceneRuleAdd()
    {
        return [
            //接收用户输入字段列表
            'field'   => ['noticeTitle', 'noticeType', 'noticeContent', 'status', 'remark'],
            //必填字段列表
            'required' => ['noticeTitle', 'noticeType', 'noticeContent', 'status'],
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
            'field'   => ['noticeId', 'noticeTitle', 'noticeType', 'noticeContent', 'status', 'remark'],
            //必填字段列表
            'required' => ['noticeId', 'noticeTitle', 'noticeType', 'noticeContent', 'status'],
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
            'field'   => ['noticeTitle', 'noticeType', 'status', 'createTime'],
            //必填字段列表
            'required' => [],
            //场景专属验证规则列表
            'rule'    => ['createTime' => 'date_between', ]
        ];
    }
}
