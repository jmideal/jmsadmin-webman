<?php

namespace plugin\jmsadmin\app\model\system;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use plugin\jmsadmin\basic\BasicModel;

/**
 * 部门Model
 * @author JM Code Generator
 */
class DeptModel extends BasicModel
{
    use SoftDeletes;

    const DELETED_AT = 'delete_time';

    /**
     * 与模型关联的表名
     * @var string
     */
    protected $table = 'sys_dept';

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'dept_id';

    /**
     * 自定义属性开始
     * 调用BasicService中的方法才会生效
     */

    /**
     * 需要检测唯一性的字段
     * @var string[]
     */
    protected $uniqueField = [['dept_name', 'parent_id']];

    /**
     * 默认的排序字段
     * @var string
     */
    protected $orderField = 'order_num';

    /**
     * 自定义属性结束
     */
    public function scopeAccessControl(Builder $query, $haveDeptId = false): void
    {
        $deptScope = getDeptDataScope($haveDeptId);
        $deptScope = empty($deptScope) ? [0] : $deptScope;
        $query->whereIn('dept_id', $deptScope);
    }
    /**
     * 定义搜索器开始
     */


    /**
     * 定义部门名称搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchDeptNameAttr($query, $value, $data)
    {
        $query->where('dept_name','like', '%' . $value . '%');
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