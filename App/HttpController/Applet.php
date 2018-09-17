<?php
namespace App\HttpController;
/**
 * Created by PhpStorm.
 * User: flanche
 * Date: 2018/9/13
 * Time: 15:56
 */

use EasyWeChat\Factory;

class RedPack extends Base
{
    public function index()
    {
        parent::index(); // TODO: Change the autogenerated stub
        $config = [
            'app_id' => 'wx3cf0f39249eb0exx',
            'secret' => 'f1c242f4f28f735d4687abb469072axx',

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => Config::getInstance()->getConf('LOG_DIR').'/wechat.log',
            ],
        ];

        $app = Factory::miniProgram($config);
    }
}