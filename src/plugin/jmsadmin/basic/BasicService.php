<?php

namespace plugin\jmsadmin\basic;

use plugin\jmsadmin\exception\ApiException;
use plugin\jmsadmin\utils\Convert;
use ReflectionClass;

class BasicService
{
    /**
     * @var $validate BasicValidate
     */
    private $validate;

    /**
     * @var $model BasicModel
     */
    protected $model;

    public function __construct($validate = null)
    {
        if ($validate instanceof BasicValidate) {
            $this->validate = $validate;
        }
    }

    public function getPk()
    {
        return $this->model->getKeyName();
    }

    protected function getValidate()
    {
        if ($this->validate instanceof BasicValidate) {
            return $this->validate;
        } else {
            $validateClass = getValidateClassByServiceClass(get_called_class());
            try {
                $reflection = new ReflectionClass($validateClass);
                $this->validate = $reflection->newInstance();
            } catch (\ReflectionException $e) {

            }
            return $this->validate;
        }
    }

    // 查询
    public function getList($params)
    {
        return $this->getListWithPage($params);
    }

    public function getInfo($id)
    {
        return $this->model->newQuery()->find($id)->toArray();
    }

    public function getListWithPage($params = [], $where = [], $order = [], $query = null)
    {
        $pageNum = request()->input('pageNum', 1);
        $pageNum = intval($pageNum) > 0 ? intval($pageNum) : 1;

        $pageSize = request()->input('pageSize', 10);
        $pageSize = intval($pageSize) > 0 && intval($pageSize) <= 100 ? intval($pageSize) : 10;

        if (is_null($query)) {
            $query = $this->buildQuery($params, $where);
        }
        $order = $this->buildOrder($order);

        $total = $query->count();

        if (!empty($order)) {
            if (is_array(current($order))) {
                foreach ($order as $k => $v) {
                    $query->orderBy($k, $v);
                }
            } else {
                $query->orderBy(...$order);
            }
        }
        $rows = $query->limit($pageSize)->offset(($pageNum-1)*$pageSize)->get()->toArray();
        return ['rows' => $rows, 'total' => $total];
    }

    public function getListWithoutPage($params, $where = [], $order = [], $query = null)
    {
        if (is_null($query)) {
            $query = $this->buildQuery($params, $where);
        }
        $order = $this->buildOrder($order);
        if (!empty($order)) {
            if (is_array(current($order))) {
                foreach ($order as $k => $v) {
                    $query->orderBy($k, $v);
                }
            } else {
                $query->orderBy(...$order);
            }
        }
        $rows = $query->get()->toArray();
        return $rows;
    }

    public function buildQuery($search, $where = [], $query = null)
    {
        $query = is_null($query) ? $this->model->newQuery() : $query;
        foreach ($search as $k => $v) {
            if ($v === '') {
                continue;
            }
            $fun = 'search'.Convert::camelize($k).'Attr';
            if (method_exists($this->model, $fun)) {
                $this->model->$fun($query, $v, $search);
            } else {
                $columns = getTableInfo($this->model->getTable());
                if (empty($columns)) {
                    throw new ApiException('表不存在');
                }
                $fields = array_column($columns, 'Type', 'Field');
                if (!isset($fields[$k])) {
                    continue;
                }
                if (is_array($v)) {
                    if (count($v) == 2 && strtotime($v[0]) && strtotime($v[0])) {
                        $query = $query->whereBetween($k, $v);
                    } else {
                        $query = $query->whereIn($k, $v);
                    }
                } else {
                    $query = $query->where($k, $v);
                }
            }
        }
        if (!empty($where)) {
            if (!is_array(current($where))) {
                $query->where(...$where);
            } else {
                $query->where($where);
            }
        }
        return $query;
    }

    public function buildOrder($order = [])
    {
        if (empty($order)) {
            $orderField = empty($this->model->getOrderField())? $this->model->getKeyName()  : $this->model->getOrderField();
            $order = [$orderField, $this->model->getOrderType()];
        }
        return $order;
    }

    public function add($params)
    {
        $params = $this->beforeInsert($params);
        $id = $this->insert($params);
        $params[$this->getPk()] = $id;
        $this->afterInsert($params);
        return $id;
    }

    public function beforeInsert($params)
    {
        $this->verifyUnique($params);
        return $params;
    }

    public function insert($params)
    {
        $columns = getTableInfo($this->model->getTable());
        if (empty($columns)) {
            throw new ApiException('表不存在');
        }
        $Fields = array_column($columns, 'Type', 'Field');
        $modelClass = get_class($this->model);
        $model = new $modelClass;
        foreach ($params as $key => $val) {
            if (isset($Fields[$key])) {
                $model->{$key} = $val;
            }
        }
        $model->save();
        $pk = $model->getKeyName();
        return $model->{$pk};
    }

    public function afterInsert($params)
    {

    }

    public function edit($params)
    {
        $params = $this->beforeUpdate($params);
        $ret = $this->update($params);
        $this->afterUpdate($params);
        return $ret;
    }

    public function beforeUpdate($params)
    {
        $pk = $this->model->getKeyName();
        if (empty($params[$pk])) {
            throw new ApiException('缺少必须要的参数' . $pk);
        }
        $this->verifyUnique($params);
        return $params;
    }

    public function update($params)
    {
        $columns = getTableInfo($this->model->getTable());
        if (empty($columns)) {
            throw new ApiException('表不存在');
        }
        $fields = array_column($columns, 'Type', 'Field');
        $pk = $this->model->getKeyName();
        $model = $this->model->newQuery()->find($params[$pk]);
        foreach ($params as $key => $val) {
            if (isset($fields[$key])) {
                $model->{$key} = $val;
            }
        }
        $model->save();
        return true;
    }

    public function afterUpdate($params)
    {

    }

    public function remove($id)
    {
        $id = $this->beforeDelete($id);
        $ret = $this->delete($id);
        $this->afterDelete($id);
        return $ret;
    }

    public function beforeDelete($id)
    {
        $id = !is_array($id) ? [$id] : $id ;
        if (!verifyIntArray($id)) {
            throw new ApiException("参数有误");
        }
        return $id;
    }

    public function delete($id)
    {
        return $this->model::destroy($id);
    }

    public function afterDelete($id)
    {

    }

    public function verifyUnique($params)
    {
        $pk = $this->model->getKeyName();
        $pkValue = $params[$pk] ?? 0;
        $uniqueField = $this->model->getUniqueField();
        foreach ($uniqueField as $key => $fields) {
            $existData = [];
            $where = [];
            if (is_array($fields)) {
                foreach ($fields as $field) {
                    $value = $params[$field] ?? '';
                    if ($value !== '') {
                        $where[] = [$field, '=', $value];
                        $existData[$field] = $value;
                    }
                }
            } else {
                $value = $params[$fields] ?? '';
                if ($value !== '') {
                    $where[] = [$fields, '=', $value];
                    $existData[$fields] = $value;
                }
            }
            if (empty($where)) {
                continue;
            }
            $info = $this->model->newQuery()->where($where)->first();
            if (empty($info)) {
                continue;
            }
            if ($info->$pk != $pkValue) {
                $validate = $this->getValidate();
                $validateFields = $validate->getField();
                $existKV = [];
                foreach ($existData as $k => $v) {
                    $ck = lcfirst(Convert::camelize($k));
                    $existKV[] = ($validateFields[$ck] ?? $k) . ':' . $v;
                }
                throw new ApiException("数据重复(".implode(",", $existKV).")");
            }
        }
    }

}