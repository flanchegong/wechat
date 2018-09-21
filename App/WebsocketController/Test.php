<?php
/**
 * Created by PhpStorm.
 * User: yuzhang
 * Date: 2018/4/14
 * Time: 下午2:27
 */

namespace App\WebsocketController;

use EasySwoole\Core\Socket\Response;
use EasySwoole\Core\Socket\AbstractInterface\WebSocketController;
use EasySwoole\Core\Swoole\ServerManager;
use EasySwoole\Core\Swoole\Task\TaskManager;

class Test extends WebSocketController
{
    function actionNotFound(?string $actionName)
    {
        $this->response()->write("action call {$actionName} not found");
    }


    function hello()
    {
        $this->response()->write('call hello with arg:'.$this->request()->getArg('content'));
    }

    public function who(){
        $fd = $this->client()->getFd();
        $this->response()->write('your fd is '.$fd.' and detail info is '.json_encode(ServerManager::getInstance()->getServer()->connection_info($fd)));
    }

    function delay()
    {
        $this->response()->write('this is delay action');
        $client = $this->client();
        //测试异步推送
        TaskManager::async(function ()use($client){
            sleep(1);
            Response::response($client,'this is async task res'.time());
        });
    }
}