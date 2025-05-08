<?php

namespace plugin\jmsadmin\app\model\system;

use plugin\jmsadmin\basic\BasicModel;
use think\db\Query;

/**
 * 字典类型Model
 * @author JM Code Generator
 */
class DictTypeModel extends BasicModel
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'sys_dict_type';

    /**
     * 主键
     *
     * @var string
     */
    protected $primaryKey = 'dict_id';



    /**
     * 自定义属性开始
     * 调用BasicService中的方法才会生效
     */

    /**
     * 需要检测唯一性的字段
     * @var string[]
     */
    protected $uniqueField = ['dict_name', 'dict_type'];

    /**
     * 默认的排序字段
     * @var string
     */
    protected $orderField = 'dict_id';

    /**
     * 自定义属性结束
     */

    /**
     * 定义搜索器开始
     */


    /**
     * 定义字典名称搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchDictNameAttr($query, $value, $data)
    {
        $query->where('dict_name','like', '%' . $value . '%');
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