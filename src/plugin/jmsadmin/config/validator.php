<?php

return [
    'mobile' => [
        'passes'    => function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^1[3-9]\d{9}$/', $value) === 1;
        },
        'replacer'  => function ($message, $attribute, $rule, $parameters) {
            return $message;
        }
    ],
    'date_between' => [
        'passes'    => function ($attribute, $value, $parameters, $validator) {
            if (!is_array($value) || count($value) != 2 || !strtotime($value[0]) || !strtotime($value[1]) || strtotime($value[0]) > strtotime($value[1])) {
                return false;
            }
            return true;
        },
        'replacer'  => function ($message, $attribute, $rule, $parameters) {
            return $message;
        }
    ]
];