<?php

namespace plugin\jmsadmin\app\service\system;

use plugin\jmsadmin\app\model\system\DictDataModel;
use plugin\jmsadmin\basic\BasicService;

class DictDataService extends BasicService
{
    public function __construct($validate = null)
    {
        $this->model = new DictDataModel();
        parent::__construct($validate);
    }
    public function selectDictDataListByType($dictType)
    {
        return $this->model::where('dict_type', $dictType)
            ->where('status', 1)
            ->orderBy('dict_sort')
            ->get()
            ->toArray();
    }
}