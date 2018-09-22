<?php
/**
 * Created by PhpStorm.
 * User: flanche
 * Date: 2018/9/22
 * Time: 7:25
 */

namespace App;

use EasySwoole\Config;
use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use duncan3dc\Laravel\BladeInstance;
use App\HttpController\Base;
/**
 * 视图控制器
 * Class ViewController
 * @author  : evalor <master@evalor.cn>
 * @package App
 */
abstract class ViewController extends Base
{
    protected $view;

    /**
     * 初始化模板引擎
     * ViewController constructor.
     * @param string   $actionName
     * @param Request  $request
     * @param Response $response
     */
    function __construct(string $actionName, Request $request, Response $response)
    {
        $tempPath   = Config::getInstance()->getConf('TEMP_DIR');    # 临时文件目录
        $this->view = new BladeInstance(EASYSWOOLE_ROOT . '/Views', "{$tempPath}/templates_c");

        parent::__construct($actionName, $request, $response);
    }

    /**
     * 输出模板到页面
     * @param string $view
     * @param array  $params
     * @author : evalor <master@evalor.cn>
     */
    function render(string $view, array $params = [])
    {
        $content = $this->view->render($view, $params);
        $this->response()->write($content);
    }
}