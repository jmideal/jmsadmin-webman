<?php

namespace plugin\jmsadmin\basic;

use plugin\jmsadmin\utils\Util;
use support\Db;
use support\Model;


class BasicModel extends Model
{
    protected $connection = 'plugin.jmsadmin.mysql';
    /**
     * 指示是否自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 时间戳存储格式
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 指示模型主键是否递增
     *
     * @var bool
     */
    public $incrementing = true;

    // 定义时间戳字段名
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    // 定义数据范围控制字段
    const DATA_LIMIT_FIELD = '';


    /**
     * 自定义属性开始，调用BasicService中的方法才会生效
     */
    //定义唯一字段
    protected $uniqueField = [];
    // 定义排序字段
    protected $orderField = '';

    // 定义排序方式
    protected $orderType = 'ASC';
    /**
     * 自定义属性结束
     */


    public function getOrderField()
    {
        return $this->orderField;
    }
    public function getOrderType()
    {
        return $this->orderType;
    }
    public function getUniqueField()
    {
        return $this->uniqueField;
    }

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format($this->getDateFormat());
    }

    public function getDataLimitColumn(): string
    {
        return defined(static::class.'::DATA_LIMIT_FIELD') ? static::DATA_LIMIT_FIELD : '';
    }

    public function getQualifiedDataLimitColumn()
    {
        return $this->qualifyColumn($this->getDataLimitColumn());
    }

    public function qualifyColumn($column)
    {
        if (str_contains($column, '.')) {
            return $column;
        }
        if (strpos(strtolower($this->getTable()), ' as ') > 0) {
            preg_match('/^[\s|\w]+as\s+(\w+)\s*$/i',
                $this->getTable(), $matches);
            $as = $matches[1] ?? '';
        }
        if (!empty($as)) {
            return $as.'.'.$column;
        } else {
            return $this->getTable().'.'.$column;
        }

    }
}