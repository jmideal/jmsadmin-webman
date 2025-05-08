<?php

namespace plugin\jmsadmin\app\model\system;

use plugin\jmsadmin\basic\BasicModel;

class MenuModel extends BasicModel
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'sys_menu';

    /**
     * 主键
     *
     * @var string
     */
    protected $primaryKey = 'menu_id';

    /**
     * 自定义属性开始
     * 调用BasicService中的方法才会生效
     */

    /**
     * 需要检测唯一性的字段
     * @var string[]
     */
    protected $uniqueField = ['path', ['parent_id', 'menu_name']];

    /**
     * 默认的排序字段
     * @var string
     */
    protected $orderField = 'order_num';

    /**
     * 自定义属性结束
     */
}