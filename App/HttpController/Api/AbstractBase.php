<?php
/**
 * Created by PhpStorm.
 * User: Gong
 * Date: 2018/9/22
 * Time: 7:51
 */

namespace App\HttpController\Api;
use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Http\Message\Status;
abstract class AbstractBase extends Controller
{
    function index()
    {
        // TODO: Implement index() method.
        $this->actionNotFound('index');
    }
    protected function actionNotFound($action): void
    {
        $this->writeJson(Status::CODE_NOT_FOUND);
    }
}