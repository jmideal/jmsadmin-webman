<?php

namespace plugin\jmsadmin\app\model\system;

use plugin\jmsadmin\basic\BasicModel;

/**
 * 通知公告Model
 * @author JM Code Generator
 */
class NoticeModel extends BasicModel
{
    /**
     * 与模型关联的表名
     * @var string
     */
    protected $table = 'sys_notice';

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'notice_id';

    /**
     * 自定义属性开始
     * 调用BasicService中的方法才会生效
     */

    /**
     * 需要检测唯一性的字段
     * @var string[]
     */
    protected $uniqueField = ['notice_title'];

    /**
     * 默认的排序字段
     * @var string
     */
    protected $orderField = 'notice_id';

    /**
     * 自定义属性结束
     */

    /**
     * 定义搜索器开始
     */


    /**
     * 定义公告标题搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchNoticeTitleAttr($query, $value, $data)
    {
        $query->where('notice_title','like', '%' . $value . '%');
    }

    /**
     * 定义创建时间搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchCreateTimeAttr($query, $value, $data)
    {
        $query->whereBetween('create_time', $value);
    }

    /**
     * 定义搜索器结束
     */

}