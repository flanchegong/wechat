<?php
/**
 * Created by PhpStorm.
 * User: Gong
 * Date: 2018/9/22
 * Time: 8:18
 */

namespace App\Sock\Controller;
use EasySwoole\Core\Socket\Response;
use EasySwoole\Core\Socket\AbstractInterface\TcpController;
use EasySwoole\Core\Swoole\ServerManager;
use EasySwoole\Core\Swoole\Task\TaskManager;
class Tcp extends TcpController
{
    function actionNotFound(?string $actionName)
    {
        $this->response()->write("{$actionName} not found");
    }
    function test()
    {
        $this->response()->write(time());
    }
    function args()
    {
        var_dump($this->request()->getArgs());
    }
    function delay()
    {
        $client = $this->client();
        TaskManager::async(function ()use($client){
            sleep(1);
            Response::response($client,'this is delay message at '.time());//为了保持协议一致，实际生产环境请调用Parser encoder
        });
    }
    function close()
    {
        $this->response()->write('you are goging to close');
        $client = $this->client();
        TaskManager::async(function ()use($client){
            sleep(2);
            ServerManager::getInstance()->getServer()->close($client->getFd());
        });
    }
    function who()
    {
        $this->response()->write('you fd is '.$this->client()->getFd());
    }
}