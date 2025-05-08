<?php

namespace plugin\jmsadmin\app\service\system;

use plugin\jmsadmin\app\model\system\DeptModel;
use plugin\jmsadmin\basic\BasicService;
use plugin\jmsadmin\exception\ApiException;
use plugin\jmsadmin\utils\Util;

class DeptService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new DeptModel();
        parent::__construct($validate);
    }
    public function getListWithoutPage($params, $where = [], $order = [], $query = null)
    {
        $query = ($this->buildQuery($params, $where, $query))->accessControl(false);
        return parent::getListWithoutPage($params, $where, $order, $query);
    }

    public function getInfo($id)
    {
        return $this->model->accessControl(true)->where('dept_id', $id)->firstOrFail()->toArray();
    }

    public function getChildDeptList($deptId)
    {
        return $this->model->whereRaw("find_in_set(?, ancestors)", [intval($deptId)])->get()->toArray();
    }

    public function selectDeptTreeList()
    {
        $depts = $this->model->accessControl(false)->orderBy('parent_id')->orderBy('order_num')->get()->toArray();
        $deptsTree = $this->buildDeptTree($depts);
        return $deptsTree;
    }

    public function buildDeptTree($depts)
    {
        $deptIds = array_column($depts, 'dept_id');
        $returnList = [];
        foreach ($depts as $dept) {
            if (!in_array($dept['parent_id'], $deptIds)) {
                $dept = $this->recursionFn($depts, $dept);
                array_push($returnList, $dept);
            }
        }
        if (empty($returnList)) {
            $returnList = $depts;
        }
        return $returnList;
    }

    public function recursionFn($depts, $dept)
    {
        $childList = $this->getChildList($depts, $dept);
        foreach ($childList as $key => $row) {
            if (!empty($this->getChildList($depts, $row))) {
                $childList[$key] = $this->recursionFn($depts, $row);
            }
        }
        $dept['children'] = $childList;
        return $dept;
    }

    public function getChildList($depts, $dept)
    {
        $list = [];
        foreach ($depts as $val) {
            if ($val['parent_id'] == $dept['dept_id']) {
                array_push($list, $val);
            }
        }
        return $list;
    }

    public function selectDeptListByRoleId($roleId)
    {
        $roleService = new RoleService();
        $role = $roleService->getInfo($roleId);

        $query = $this->model->setTable($this->model->getTable() . " as d")->newQuery();
        $query->select("d.dept_id")
            ->leftJoin('sys_role_dept as rd', 'd.dept_id', '=', 'rd.dept_id')
            ->where('rd.role_id', $roleId);
        if ($role['dept_check_strictly']) {
            $notQuery = $this->model->setTable($this->model->getTable() . " as d")->newQuery()
                ->select("d.parent_id")
                ->join('sys_role_dept as rd', 'd.dept_id', '=', 'rd.dept_id')
                ->where('rd.role_id', $roleId);
            $query->whereNotIn('d.dept_id', $notQuery);
        }
        return $query->orderBy("d.parent_id")->orderBy("d.order_num")->pluck('d.dept_id')->toArray();
    }

    public function beforeInsert($params)
    {
        $deptScope = getDeptDataScope(false);
        if (empty($params['parent_id'])) {
            throw new ApiException('请选择父级部门');
        }
        if (!in_array($params['parent_id'], $deptScope)) {
            throw new ApiException('没有权限设置该父级部门');
        }
        $params = parent::beforeInsert($params);
        $info = $this->model::accessControl(false)->where('dept_id', $params['parent_id'])->firstOrFail()->toArray();
        if ($info['status'] == 0 && $params['status'] == 1) {
            throw new ApiException('上级部门已禁用，不能启用当前部门');
        }
        $params['ancestors'] = $info['ancestors'] . ',' . $params['parent_id'];
        return $params;
    }
    public function beforeUpdate($params)
    {
        $params = parent::beforeUpdate($params);
        if ($params['parent_id'] == $params['dept_id']) {
            throw new ApiException('上级部门不能是自己');
        }
        $deptScope = getDeptDataScope(false);
        if ($params['parent_id'] == 0) {
            if (!in_array(0, $deptScope)) {
                throw new ApiException('没有权限设置该父级部门');
            }
            $params['ancestors'] = 0;
        } else {
            //如果没有父级部门权限，判断所属父级是否改变
            if (!in_array($params['parent_id'], $deptScope)) {
                $dept = $this->model::where('dept_id', $params['dept_id'])->firstOrFail()->toArray();
                if ($dept['parent_id'] == $params['parent_id']) {
                    $info = $this->model::where('dept_id', $params['parent_id'])->firstOrFail()->toArray();
                } else {
                    throw new ApiException('没有权限设置该父级部门');
                }
            } else {
                $info = $this->model::where('dept_id', $params['parent_id'])->firstOrFail()->toArray();
            }
            if (empty($info)) {
                throw new ApiException('上级部门不存在');
            }
            if ($info['status'] == 0 && $params['status'] == 1) {
                throw new ApiException('上级部门已禁用，不能启用当前部门');
            }
            $params['ancestors'] = $info['ancestors'] . ',' . $params['parent_id'];
        }
        return $params;
    }
    public function update($params)
    {
        $children = $this->model->whereRaw('find_in_set(?, ancestors)', [$params['dept_id']])->get();
        $info = $this->getInfo($params['dept_id']);
        if (empty($info)) {
            throw new ApiException('部门不存在');
        }
        Util::getDb()->beginTransaction();
        try {
            $oldAncestors = $info['ancestors'];
            $newAncestors = $params['ancestors'];
            foreach ($children as $child) {
                $child->ancestors = $newAncestors . substr($child->ancestors, strlen($oldAncestors));
                if ($params['status'] == 0) {
                    $child->status = 0;
                }
                $child->save();
            }
            $ret = parent::update($params);
            Util::getDb()->commit();
        } catch (\Exception $e) {
            Util::getDb()->rollBack();
            throw $e;
        }
        return $ret;
    }

    public function beforeDelete($id)
    {
        $id = parent::beforeDelete($id);
        foreach ($id as $key => $val) {
            if ($this->model->where('parent_id', $val)->count() > 0) {
                throw new ApiException('存在下级部门,不允许删除');
            }
            if ((new UserService())->model->where('dept_id', $val)->count() > 0) {
                throw new ApiException('部门存在用户,不允许删除');
            }
        }
        return $id;
    }
}