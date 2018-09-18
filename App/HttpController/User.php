<?php
/**
 * Created by PhpStorm.
 * User: flanche
 * Date: 2018/9/14
 * Time: 17:11
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
use WeMini\Crypt;

class User extends Base
{
    /**
     * 小程序授权，获取用户手机号码
     */
    public function getPhone(){
        $appid =$_GET['appid'];
        $secret =$_GET['secret'];
        $js_code=$_GET['code'];
        $iv = ($_GET['iv']);
        $encryptedData=($_GET['encryptedData']);
        $grant_type='authorization_code';

        $objSession=http_curl("https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code=$js_code&grant_type=$grant_type");
        $session_key = json_decode($objSession)->session_key;

        //$decodeData = new WXBizDataCrypt($appid, $session_key);
        $errCode =(new Crypt())->decryptData($iv,$session_key, $encryptedData );

        if ($errCode == 0) {
            print($errCode . "\n");
        } else {
            print($errCode . "\n");
        }

        function http_curl($url){
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,$url);
            curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,30);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            $response=curl_exec($curl);
            curl_close($curl);
            return $response;
        }
    }
}