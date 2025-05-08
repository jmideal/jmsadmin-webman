<?php

namespace plugin\jmsadmin\annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class LogInfo
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}