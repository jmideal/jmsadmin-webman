<?php

namespace plugin\jmsadmin\app\middleware;

use plugin\jmsadmin\app\service\monitor\OperLogService;
use Webman\MiddlewareInterface;
use plugin\jmsadmin\basic\Response;
use Webman\Http\Request;
class OperationLog implements MiddlewareInterface
{
    public function process(Request $request, callable $handler) : Response
    {
        $request->__startTime = microtime(true);
        $response = $handler($request); // 继续向洋葱芯穿越，直至执行控制器得到响应
        if ($response instanceof Response) {
            $operLogService = new OperLogService();
            $message = $response->getMessage();
            $rawBody = $response->rawBody();
            if ($response->getStatusCode() == 200) {
                if ($response->getBusinessStatus() == 200) {
                    $operLogService->logInsert(1, $message, $rawBody);
                } else {
                    $operLogService->logInsert(0, $message, $rawBody);
                }
            } else {
                $operLogService->logInsert(0, $message, $rawBody);
            }
        }
        return $response;
    }
}