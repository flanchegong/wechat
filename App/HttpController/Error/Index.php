<?php
/**
 * Created by PhpStorm.
 * User: Gong
 * Date: 2018/9/22
 * Time: 7:57
 */

namespace App\HttpController\Error;
use EasySwoole\Core\Http\AbstractInterface\Controller;
class Index extends Controller
{
    function index()
    {
        // TODO: Implement index() method.
        //error  并不会被响应到客户端中。
        echo $a;
        $this->response()->write('error index');
    }
    function fatal()
    {
        //未重构本控制器异常处理的时候
        $test = new XXXXXXX();
        $this->response()->write('error fatal');
    }
}