<?php

namespace plugin\jmsadmin\app\controller\system;

use plugin\jmsadmin\annotation\LogInfo;
use plugin\jmsadmin\basic\BasicController;
use plugin\jmsadmin\utils\ApiResult;
use plugin\jmsadmin\utils\Random;
use support\Request;
use support\Response;

#[LogInfo(name: "公共权限")]
class CommonController extends BasicController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function upload(Request $request): Response
    {
        $file = $request->file('file');
        if ($file && $file->isValid()) {
            if (!in_array(strtolower($file->getUploadExtension()), ['jpg', 'jpeg', 'png', 'avif'])) {
                return ApiResult::error("只支持'jpg', 'jpeg', 'png', 'avif'类型文件");
            }
            $uploadPath = config('plugin.jmsadmin.app.upload_path');
            $dir = '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
            $fileName = Random::uuid() . '.' . $file->getUploadExtension();
            $file->move($uploadPath . $dir . $fileName);
            return ApiResult::success(['fileName' => $fileName, 'filePath' => $dir . $fileName, 'ext' => $file->getUploadExtension()]);
        }
        return ApiResult::error("上传出现错误");
    }
}