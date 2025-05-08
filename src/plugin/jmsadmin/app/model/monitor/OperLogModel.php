<?php

namespace plugin\jmsadmin\app\model\monitor;

use plugin\jmsadmin\basic\BasicModel;

/**
 * 操作日志记录Model
 * @author JM Code Generator
 */
class OperLogModel extends BasicModel
{
    /**
     * 与模型关联的表名
     * @var string
     */
    protected $table = 'sys_oper_log';

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'oper_id';

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
    protected $orderField = 'oper_time';

    protected $orderType = 'DESC';

    /**
     * 自定义属性结束
     */

    /**
     * 定义搜索器开始
     */

    /**
     * 定义操作IP搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchOperIpAttr($query, $value, $data)
    {
        $query->where('oper_ip','like', $value . '%');
    }

    /**
     * 定义模块名称搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchModuleNameAttr($query, $value, $data)
    {
        $query->where('module_name','like', '%' . $value . '%');
    }

    /**
     * 定义功能名称搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchControllerNameAttr($query, $value, $data)
    {
        $query->where('controller_name','like', '%' . $value . '%');
    }

    /**
     * 定义操作名称搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchActionNameAttr($query, $value, $data)
    {
        $query->where('action_name','like', '%' . $value . '%');
    }

    /**
     * 定义操作时间搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchOperTimeAttr($query, $value, $data)
    {
        $query->whereBetween('oper_time', $value);
    }

    /**
     * 定义搜索器结束
     */

}