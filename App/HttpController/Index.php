<?php
/**
 * Created by PhpStorm.
 * User: flanche
 * Date: 2018/9/21
 * Time: 23:54
 */

namespace App\HttpController;
use EasySwoole\Config;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\DecryptException;
use App\Utility\Tools;
use App\Model\Profile;
use Illuminate\Database\Capsule\Manager as Capsule;
use WeMini\Crypt;
use App\Model\Visitors;
use App\Model\Redpack;
use App\ViewController;
use EasySwoole\Core\Http\Message\Status;
use EasySwoole\Core\Swoole\ServerManager;
class Index extends ViewController
{
    public function index()
    {
        // TODO: Implement index() method.
        $this->response()->write('hello world');
        // Blade View
        $this->render('index');     # 对应模板: Views/index.blade.php
    }

    //测试路径 /test/index.html
    function test()
    {
        $ip = ServerManager::getInstance()->getServer()->connection_info($this->request()->getSwooleRequest()->fd);
        var_dump($ip);
        $ip2 = $this->request()->getHeaders();
        var_dump($ip2);
        $this->response()->write('index controller test');
    }
    /*
     * protected 方法对外不可见
     *  测试路径 /hide/index.html
     */
    protected function hide()
    {
        var_dump('this is hide method');
    }
    protected function actionNotFound($action): void
    {
        $this->response()->withStatus(Status::CODE_NOT_FOUND);
        $this->response()->write("{$action} is not exist");
    }
    function a()
    {
        $this->response()->write('index controller router');
    }
    function a2()
    {
        $this->response()->write('index controller router2');
    }
    function test2(){
        $this->response()->write('this is controller test2 and your id is '.$this->request()->getRequestParam('id'));
    }


}