<?php

namespace plugin\jmsadmin\annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class LogInfo
{
    public $name;
    public $withResult = true;
    public $withParams = true;

    public function __construct($name, $withResult = true, $withParams = true)
    {
        $this->name = $name;
        $this->withResult = $withResult;
        $this->withParams = $withParams;
    }
}