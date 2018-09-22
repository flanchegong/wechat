<?php
/**
 * Created by PhpStorm.
 * User: Gong
 * Date: 2018/9/22
 * Time: 8:09
 */

namespace App\HttpController\Task;
use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Swoole\Task\TaskManager;
class Index extends Controller
{
    function index()
    {
        // TODO: Implement index() method.
        $this->actionNotFound('task');
    }
    function async()
    {
        TaskManager::async(function (){
            sleep(2);
            var_dump('this is async task');
        });
        $this->response()->write('async task add');
    }
}