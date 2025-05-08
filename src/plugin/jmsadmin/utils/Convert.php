<?php

namespace plugin\jmsadmin\utils;

use plugin\jmsadmin\exception\ApiException;

class Convert
{
    static public function Boolean($value)
    {
        return self::toBool($value, null);
    }

    /**
     * @throws ApiException
     */
    static public function toBool($value, $defaultValue)
    {
        if (is_null($value)) {
            return $defaultValue;
        }
        if (is_bool($value)) {
            return $value;
        }
        if (is_array($value) || is_object($value)) {
            throw new ApiException("参数有误", 500);
        }
        $value = (string)$value;
        switch ($value)
        {
            case "true":
            case "yes":
            case "ok":
            case "1":
                return true;
            case "false":
            case "no":
            case "0":
            case "":
                return false;
            default:
                return $defaultValue;
        }
    }
    /**
     * 其他命名转驼峰
     * @param $string
     * @param $separator
     * @return array|string|string[]
     */
    static public function camelize($string, $separator = '_')
    {
        //$words = str_replace($separator, " ", strtolower($string));
        $words = str_replace($separator, " ", $string);
        return str_replace(" ", "", ucwords($words));
    }

    /**
     * 驼峰命名转其他命名
     * @param $string
     * @param $separator
     * @return string
     */
    static public function unCamelize($string, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $string));
    }

    static public function toLowerCamelCase($string, $separator = '_')
    {
        $upperCamelCaseString = self::camelize($string, $separator);
        return lcfirst($upperCamelCaseString);
    }

    static public function toLowerCamelCaseArray($value)
    {
        if (is_object($value)) {
            $value = (array)$value;
        }
        if (is_array($value)) {
            reset($value);
            foreach ($value as $k => $v) {
                unset($value[$k]);
                $value[self::toLowerCamelCase($k)] = self::toLowerCamelCaseArray($v);
            }
            return $value;
        }
        return $value;
    }
    static public function unCamelizeArray($value)
    {
        if (is_object($value)) {
            $value = (array)$value;
        }
        if (is_array($value)) {
            reset($value);
            foreach ($value as $k => $v) {
                unset($value[$k]);
                $value[self::unCamelize($k)] = self::unCamelizeArray($v);
            }
            return $value;
        }
        return $value;
    }
}