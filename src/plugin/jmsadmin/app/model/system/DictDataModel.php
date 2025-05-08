<?php

namespace plugin\jmsadmin\app\model\system;

use plugin\jmsadmin\basic\BasicModel;

/**
 * 字典数据Model
 * @author JM Code Generator
 */
class DictDataModel extends BasicModel
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'sys_dict_data';

    /**
     * 主键
     *
     * @var string
     */
    protected $primaryKey = 'dict_code';

    /**
     * 自定义属性开始
     * 调用BasicService中的方法才会生效
     */

    /**
     * 需要检测唯一性的字段
     * @var string[]
     */
    protected $uniqueField = [['dict_type', 'dict_label'], ['dict_type', 'dict_value']];

    /**
     * 默认的排序字段
     * @var string
     */
    protected $orderField = 'dict_sort';

    /**
     * 自定义属性结束
     */

    /**
     * 定义搜索器开始
     */
}