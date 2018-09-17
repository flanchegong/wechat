<?php
/**
 * Created by PhpStorm.
 * User: flanche
 * Date: 2018/9/13
 * Time: 14:50
 */

namespace App\HttpController;


use EasySwoole\Config;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\Logger;

use EasySwoole\Core\Http\AbstractInterface\Controller;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;


use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\DecryptException;
use App\Utility\Tools;
use App\Model\Profile;
use Illuminate\Database\Capsule\Manager as Capsule;

class Wechat extends Base
{

    function index()
    {
        $version = Capsule::select('select version();');
        $this->response()->write($version);
    }


    protected function getMiniProgramConfig(){
        return [
            'app_id' => 'wx2dfe229cdd42a7c0',
            'secret' => 'c83e2ffe11f65ef4603b75f398bd6b36',
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => Config::getInstance()->getConf('LOG_DIR').'/wechat.log',
            ],
        ];
    }

    function getToken(){
        $code = $this->request()->getQueryParam('code');
        $app = Factory::miniProgram($this->getMiniProgramConfig());
        try {
            $ret = $app->auth->session($code);
            if(!isset($ret['session_key'])){
                logger::getInstance()->log('微信session_key获取失败:('.$ret['errcode'].')'.$ret['errmsg']);
                throw new \Exception('系统繁忙，请稍后再试', 101);
            }
            $this->success($ret);
        }catch (\Exception $e){
            $this->error($e->getCode(),$e->getMessage());
        }
    }


    function login(){
        $app = Factory::miniProgram($this->getMiniProgramConfig());
        $args = $this->request()->getParsedBody();

        try{
            $res =  $app->encryptor->decryptData($args['session_key'], $args['iv'], $args['encryptedData']);
            if(!$res){
                $this->error(105,'获取用户信息失败，请稍后再试');
            }
            print_r($res);
            if( $uid = (new Profile())->getOrInsert($res['openId'], $res)){
                $this->success(['session_id' => Tools::sessionEncrypt($uid)]);
            }

        }catch(DecryptException $e){
            $this->error(102, '解密数据错误,请重新登录');
        }catch (\Exception $e){
            $this->error($e->getCode(), $e->getMessage());
        }
    }

    function check(){
        $header = $this->request()->getHeaders();
        print_r($header);
        if(!isset($header['authorization'])){
            $this->error(103,'access denied');
        }

        list ($bearer, $token) = explode(' ',$header['authorization'][0]);

        if(!$token){
            $this->error(104,'token error');
        }

        if(Tools::sessionCheckToken($token)){
            $this->success();
        }else{
            $this->error(106,'check token error');
        }
    }


}