<?php

namespace plugin\jmsadmin\app\model\system;

use plugin\jmsadmin\basic\BasicModel;

/**
 * 用户和角色关联Model
 * @author JM Code Generator
 */
class UserRoleModel extends BasicModel
{
    /**
     * 与模型关联的表名
     * @var string
     */
    protected $table = 'sys_user_role';

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = '';

    public $timestamps = false;

    public $incrementing = false;

    /**
     * 自定义属性开始
     * 调用BasicService中的方法才会生效
     */

    /**
     * 需要检测唯一性的字段
     * @var string[]
     */
    protected $uniqueField = [];

    /**
     * 默认的排序字段
     * @var string
     */
    protected $orderField = 'user_id';

    /**
     * 自定义属性结束
     */

    /**
     * 定义搜索器开始
     */


    /**
     * 定义搜索器结束
     */

}