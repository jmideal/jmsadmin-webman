<?php

namespace plugin\jmsadmin\basic;
use support\Response as WebmanResponse;

class Response extends WebmanResponse
{
    protected $businessStatus;
    protected $message;

    function __construct($content, $status = 200, $headers = array(), $businessStatus = 200, $message = '')
    {
        $this->businessStatus = $businessStatus;
        $this->message = $message;
        parent::__construct($content, $status, $headers);
    }

    public function setBusinessStatus($status)
    {
        $this->businessStatus = $status;
    }

    public function getBusinessStatus()
    {
        return $this->businessStatus;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
}