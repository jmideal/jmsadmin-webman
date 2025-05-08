<?php

namespace plugin\jmsadmin\app\model\system;

use Illuminate\Database\Eloquent\SoftDeletes;
use plugin\jmsadmin\app\model\scopes\AccessDataScope;
use plugin\jmsadmin\basic\BasicModel;

/**
 * 用户信息Model
 * @author JM Code Generator
 */
class UserModel extends BasicModel
{
    use SoftDeletes;

    const DELETED_AT = 'delete_time';

    /**
     * 与模型关联的表名
     * @var string
     */
    protected $table = 'sys_user';

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'user_id';


    /**
     * 自定义属性开始
     * 调用BasicService中的方法才会生效
     */

    /**
     * 需要检测唯一性的字段
     * @var string[]
     */
    protected $uniqueField = ['user_name', 'phonenumber', 'email'];

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
     * 定义用户手机号搜索器
     * @param $query
     * @param $value
     * @param $data
     * @return void
     */
    public function searchPhonenumberAttr($query, $value, $data)
    {
        $query->where('phonenumber','like', '%' . $value . '%');
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