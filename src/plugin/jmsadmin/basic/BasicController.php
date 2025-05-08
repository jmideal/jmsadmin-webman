<?php

namespace plugin\jmsadmin\basic;

use plugin\jmsadmin\utils\ApiResult;
use plugin\jmsadmin\utils\Convert;
use support\Request;
use support\Response;

class BasicController
{
    /**
     * @var $service BasicService
     */
    protected $service;

    /**
     * @var $validate BasicValidate
     */
    protected $validate;

    public function __construct()
    {

    }

    public function list(Request $request):Response
    {
        $params = $this->validate->run('search', $request->get());
        $ret = $this->service->getList($params);
        return ApiResult::success($ret);
    }

    public function info(Request $request):Response
    {
        $pk = $this->service->getPk();
        $pk = lcfirst(Convert::camelize($pk));
        $id = $request->get($pk, '0');
        if (!validatorSingle($id, 'required|integer|min:1')) {
            return ApiResult::error("参数有误");
        }
        $ret = $this->service->getInfo($id);
        return ApiResult::success($ret);
    }

    public function add(Request $request):Response
    {
        $params = $this->validate->run('add', $request->post());
        $adminInfo = adminInfo();
        $params['create_by'] = $adminInfo['user_id'];
        $ret = $this->service->add($params);
        return $ret ? ApiResult::success() : ApiResult::error() ;
    }

    public function edit(Request $request):Response
    {
        $params = $this->validate->run('edit', $request->post());
        $adminInfo = adminInfo();
        $params['update_by'] = $adminInfo['user_id'];
        $ret = $this->service->edit($params);
        return $ret ? ApiResult::success() : ApiResult::error() ;
    }

    public function remove(Request $request):Response
    {
        $pk = $this->service->getPk();
        $pk = lcfirst(Convert::camelize($pk));
        $id = $request->post($pk, []);
        $this->service->remove($id);
        return ApiResult::success();
    }
}