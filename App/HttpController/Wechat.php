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
use WeMini\Crypt;
use App\Model\Visitors;
use App\Model\Redpack;
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

     function getPhone(){
         $app = Factory::miniProgram($this->getMiniProgramConfig());
         $args = $this->request()->getParsedBody();
         //$args['iv']='C0Hh53Vp+oow7Ah+MsX1HA==';
         //$args['encryptedData']='1YqqRT9pBAFEet2oJkEKZkwBT4RDi5Ojfll21J/tcs78MLvEDGwNRUOFEVUbzCGlYTh1RgrIQSmm/FBRhKZlcQedeoXV547VB3ujm3dDDo22Ovbycr7aHMNZn775qjHvt1pXBXl6M9YDm9o0u8I8pHeqfByb1Ggkua4lg3l9UGzkI9ki8Gqrs7+8FaR419SPUqCsKlXv3Fi2M7ujifK97A==';
         try{
             //$token='Bk2vwyySaBeG7IeUJ5VzqMka2kiePdai';//
             $token=$this->request()->getRequestParam('token');

             $visitors = (new Visitors())->getSessionKeyByToken($token);
             //var_dump($visitors);
             if(empty($visitors['session_key'])){
                 $this->error(110,'sessionKey 获取失败');
             }
             $res =  $app->encryptor->decryptData($visitors['session_key'], $args['iv'], $args['encryptedData']);
             if(!$res){
                 $this->error(105,'获取手机号码失败，请稍后再试');
             }
             print_r($res);
             $rs= (new Redpack())->getOrInsert($res,$visitors);
             if($rs){
				 $data['status']=1;
				 $data['result']='手机号码解析成功';
                 $this->ajaxReturn($data,'JSON');
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