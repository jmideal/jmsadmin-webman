<?php

namespace plugin\jmsadmin\app\model\system;

use Illuminate\Database\Eloquent\SoftDeletes;
use plugin\jmsadmin\basic\BasicModel;

/**
 * 角色信息Model
 * @author JM Code Generator
 */
class RoleModel extends BasicModel
{
    use SoftDeletes;

    const DELETED_AT = 'delete_time';

    /**
     * 与模型关联的表名
     * @var string
     */
    protected $table = 'sys_role';

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'role_id';

    /**
     * 自定义属性开始
     * 调用BasicService中的方法才会生效
     */

    /**
     * 需要检测唯一性的字段
     * @var string[]
     */
    protected $uniqueField = ['role_name', 'role_key'];

    /**
     * 默认的排序字段
     * @var string
     */
    protected $orderField = 'role_sort';

    /**
     * 自定义属性结束
     */

    /**
     * 定义搜索器开始
     */


    /**
     * 定义角色名称搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchRoleNameAttr($query, $value, $data)
    {
        $query->where('role_name','like', '%' . $value . '%');
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