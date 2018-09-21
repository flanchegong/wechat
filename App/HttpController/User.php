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
        //header("Content-type: text/html; charset=utf-8");
        $request=$this->request();
        $iv = $request->getRequestParam('iv');
        $encryptedData = $request->getRequestParam('encryptedData');

        // 小程序配置
        $config = [
            'appid'     => 'wx2dfe229cdd42a7c0',
            'appsecret' => 'c83e2ffe11f65ef4603b75f398bd6b36',
        ];

// 解码数据
        $iv = '7ng6gTa4JZo98ao6bjR7Yg==';
        $code = '033kxeqL0ZeqT52F34rL0ZnkqL0kxeqe';
        $decode = 'L7VnXXNszRhhsjfSlmhKpAvTO/KThUKxGO11uP9tiI6Y0RQdHQUdGtfcKqbrpirL6VNTPFNy0kizJmYtkZ5bmnKwdPE0Gjvrh/ylkEwJQI0YPiVo3L7YnJJFEFuiCmbwjshsPGG0DAjr2U/u/Mywwz2YFC1ZA0rHdQz5TXIIBsEusRDo9BNZt5tk7Enu64z16EtaJ6JGWC1iEhwSVWBAw39QqiC5u/oSGB84NCzdTpvZDwh/6rRN1wIRbaRqmoJw3iKex+pMD5tFaD8NF/RqawjcXV6yepOPU1dBFuComunDzQzEm65jIG42P9sFJjMGLWK/j0jEbtNGz5sKKnOv7MVXIvU91ovr14GYljIo/U4F8kEYNpPYMF22hjK5R2CsjQuxJLo1PwIhmyJHHTdjIIpXGkqJP1Rod8AGxHNyeIRmAa8o4iqHb0wiluYBL+b1pOTSSD1A4MjtpZkTMX6QPeNsd7GshQkE5qJdYwpwx0fE5mQFK36VOh+p4wMYqQleraIIfG80O1lGc3UfGffyjD6Bbr1PCWtqXbewMFhk/Qo=';
        $sessionKey = 'UyIGHn13BOdev+jBeOTnWg==';
        $mini = new Crypt($config);
        echo '<pre>';
print_r($mini->session($code));
        print_r($mini->decode($iv, $sessionKey, $decode));
print_r($mini->userInfo($code, $iv, $decode));
    }
}