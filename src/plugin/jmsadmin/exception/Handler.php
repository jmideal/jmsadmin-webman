<?php

namespace plugin\jmsadmin\exception;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use support\exception\PageNotFoundException;
use Throwable;
use Webman\Exception\ExceptionHandler;
use Webman\Http\Request;
use plugin\jmsadmin\basic\Response;
use Webman\RateLimiter\RateLimitException;

class Handler extends ExceptionHandler
{
    public function render(Request $request, Throwable $exception): Response
    {
        $debug = config('plugin.jmsadmin.app.debug', true);
        if ($exception instanceof ApiException) {
            //接口异常
            $code = $exception->getCode();
            $httpCode = 200;
            $message = $exception->getMessage();
        } elseif ($exception instanceof ValidateException) {
            //验证器异常
            $code = 500;
            $httpCode = 200;
            $message = $exception->getMessage();
        } elseif ($exception instanceof RateLimitException) {
            //限流器异常
            $code = 500;
            $httpCode = 200;
            $message = $exception->getMessage();
        } elseif ($exception instanceof PageNotFoundException) {
            $code = 404;
            $httpCode = 200;
            $message = $exception->getMessage();
        } elseif ($exception instanceof ModelNotFoundException) {
            $code = 500;
            $httpCode = 200;
            $message = '未找到有效数据';
        } else {
            //系统异常
            $code = 500;
            $httpCode = 500;
            $exceptionMessage = $exception->getMessage();
            $message = "系统出现异常，请联系管理员";
        }
        $data = [
            'code' => $code,
            'msg' => $message,
        ];
        if ($debug) {
            $data['request_url'] = $request->method() . ' ' . $request->uri();
            $data['timestamp'] = date('Y-m-d H:i:s');
            $data['client_ip'] = $request->getRealIp();
            $data['request_param'] = $request->all();
            $data['exception_handle'] = get_class($exception);
            $data['exception_info'] = [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString())
            ];
        }
        return new Response($httpCode, ['Content-Type' => 'application/json;charset=utf-8'], json_encode($data, JSON_UNESCAPED_UNICODE), $code, !empty($exceptionMessage)?$exceptionMessage:$message);
    }
}