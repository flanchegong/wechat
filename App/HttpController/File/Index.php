<?php
/**
 * Created by PhpStorm.
 * User: Gong
 * Date: 2018/9/22
 * Time: 7:59
 */

namespace App\HttpController\File;
use EasySwoole\Core\Http\AbstractInterface\Controller;
class Index extends Controller
{
    function index()
    {
        // TODO: Implement index() method.
        $file = $this->request()->getUploadedFile('testFile');
        if($file){
//            $file->
        }else{
            $this->response()->write('you have not file');
        }
    }
}