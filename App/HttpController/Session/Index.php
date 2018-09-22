<?php
/**
 * Created by PhpStorm.
 * User: Gong
 * Date: 2018/9/22
 * Time: 8:03
 */

namespace App\HttpController\Session;
use EasySwoole\Core\Http\AbstractInterface\Controller;
class Index extends Controller
{
    function index()
    {
        // TODO: Implement index() method.
        $this->session()->sessionStart();
        var_dump($this->session()->sessionId());
        $this->session()->set('test',time());
        $this->response()->write('yes');
    }
    function test()
    {
        $this->session()->sessionStart();
        var_dump($this->session()->sessionId());
        var_dump($this->session()->get('test'));
        $this->response()->write('yes2');
    }
}