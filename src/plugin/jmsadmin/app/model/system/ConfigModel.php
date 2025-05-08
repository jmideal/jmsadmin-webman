<?php

namespace plugin\jmsadmin\app\model\system;

use plugin\jmsadmin\basic\BasicModel;

/**
 * 参数配置Model
 * @author JM Code Generator
 */
class ConfigModel extends BasicModel
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'sys_config';

    /**
     * 主键
     *
     * @var string
     */
    protected $primaryKey = 'config_id';

    /**
     * 自定义属性开始
     * 调用BasicService中的方法才会生效
     */

    /**
     * 需要检测唯一性的字段
     * @var string[]
     */
    protected $uniqueField = ['config_name', 'config_key'];

    /**
     * 默认的排序字段
     * @var string
     */
    protected $orderField = 'config_id';

    /**
     * 自定义属性结束
     */

    /**
     * 定义搜索器开始
     */


    /**
     * 定义参数名称搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchConfigNameAttr($query, $value, $data)
    {
        $query->where('config_name','like', '%' . $value . '%');
    }

    /**
     * 定义参数键名搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchConfigKeyAttr($query, $value, $data)
    {
        $query->where('config_key','=', $value);
    }

    /**
     * 定义参数键值搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchConfigValueAttr($query, $value, $data)
    {
        $query->where('config_value','=', $value);
    }

    /**
     * 定义系统内置搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchConfigTypeAttr($query, $value, $data)
    {
        $query->where('config_type','=', $value);
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

}