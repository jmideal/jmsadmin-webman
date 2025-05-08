<?php

namespace plugin\jmsadmin\app\model\system;

use plugin\jmsadmin\app\model\scopes\AccessDataScope;
use plugin\jmsadmin\basic\BasicModel;

/**
 * 岗位信息Model
 * @author JM Code Generator
 */
class PostModel extends BasicModel
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'sys_post';

    /**
     * 主键
     *
     * @var string
     */
    protected $primaryKey = 'post_id';

    /**
     * 自定义属性开始
     * 调用BasicService中的方法才会生效
     */

    /**
     * 需要检测唯一性的字段
     * @var string[]
     */
    protected $uniqueField = ['post_name'];

    /**
     * 默认的排序字段
     * @var string
     */
    protected $orderField = 'post_sort';

    /**
     * 自定义属性结束
     */
 
    /**
     * 定义搜索器开始
     */
    /**
     * 定义岗位名称搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchPostNameAttr($query, $value, $data)
    {
        $query->where('post_name','like', '%' . $value . '%');
    }

    /**
     * 定义岗位编码搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchPostCodeAttr($query, $value, $data)
    {
        $query->where('post_code','like', '%' . $value . '%');
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