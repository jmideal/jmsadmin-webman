<?php

namespace plugin\jmsadmin\app\model\monitor;

use plugin\jmsadmin\basic\BasicModel;

/**
 * 系统访问记录Model
 * @author JM Code Generator
 */
class LogininforModel extends BasicModel
{
    /**
     * 与模型关联的表名
     * @var string
     */
    protected $table = 'sys_logininfor';

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'info_id';

    public $timestamps = false;

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
    protected $orderField = 'login_time';

    protected $orderType = 'DESC';

    /**
     * 自定义属性结束
     */

    /**
     * 定义搜索器开始
     */


    /**
     * 定义用户账号搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchUserNameAttr($query, $value, $data)
    {
        $query->where('user_name','like', '%' . $value . '%');
    }

    /**
     * 定义登录IP地址搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchIpaddrAttr($query, $value, $data)
    {
        $query->where('ipaddr','like', '%' . $value . '%');
    }

    /**
     * 定义访问时间搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchLoginTimeAttr($query, $value, $data)
    {
        $query->whereBetween('login_time', $value);
    }

    /**
     * 定义搜索器结束
     */

}