<?php

namespace plugin\jmsadmin\basic;

use plugin\jmsadmin\exception\ValidateException;
use plugin\jmsadmin\utils\Convert;

class BasicValidate
{
    protected $field = [];

    protected $rule =   [];

    protected $message  =   [];

    public function run($scene, $data)
    {
        $fieldList = array_keys($this->field);
        $uleRet = [];
        $method = "sceneRule" . ucfirst($scene);
        if (method_exists($this, $method)) {
            $uleRet = $this->$method();
        }
        if (!empty($uleRet['field'])) {
            $fieldList = $uleRet['field'];
            $rule = !empty($uleRet['rule']) ? array_merge($this->rule, $uleRet['rule']) : $this->rule ;
            if (!empty($uleRet['required'])) {
                $required = array_fill_keys($uleRet['required'], 'required');
                $validator = validator($data, $required, $this->message, $this->field);
                if ($validator->fails()) {
                    throw new ValidateException($validator->errors()->first());
                }
            }
            $validator = validator($data, $rule, $this->message, $this->field);
            if ($validator->fails()) {
                throw new ValidateException($validator->errors()->first());
            }
        } else {
            $validator = validator($data, $this->rule, $this->message, $this->field);
            if ($validator->fails()) {
                throw new ValidateException($validator->errors()->first());
            }
        }

        $attributes = [];
        if (!empty($fieldList)) {
            foreach ($fieldList as $field) {
                $value = $data[$field] ?? null;
                if (!is_null($value)) {
                    $attributes[$field] = $value;
                }
            }
        }
        $attributes = Convert::unCamelizeArray($attributes);
        return $attributes;
    }

    public function getField()
    {
        return $this->field;
    }
}