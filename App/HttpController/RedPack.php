<?php
namespace App\HttpController;
/**
 * Created by PhpStorm.
 * User: flanche
 * Date: 2018/9/13
 * Time: 15:18
 */
use App\Utility\Loader;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use EasyWeChat\Factory;
use App\Utility\WechatPay;
use EasySwoole\Config;


/**
 * 微信支付
 * Class RedPack
 * @package App\HttpController
 */
class RedPack extends Base
{

    public function __construct($actionName, Request $request, Response $response)
    {
        parent::__construct($actionName, $request, $response);
    }
    /**
     * 红包配置信息
     * @return mixed
     */
    private function redConfig(){
        $config = [
            'app_id'    => 'wxfb94ff9d2363bb33',
            'mch_id'    => '1502277341',
            'key'       => '9dc8774024e21dbbc8b3ea8d9942c81b',
            'cert_path' => Config::getInstance()->getConf('FILE_DIR').'/20180913cert/apiclient_cert.pem',
            'key_path'  => Config::getInstance()->getConf('FILE_DIR').'/20180913cert/apiclient_key.pem',
            'notify_url'         => '默认的订单回调地址',     // 你也可以在下单时单独设置来想覆盖它
            // ...
        ];
        // 公众号
        //$app = Factory::officialAccount($config);

        // 小程序
        //$app = Factory::miniProgram($config);

        // 开放平台
        //$app = Factory::openPlatform($config);

        // 企业微信
        //$app = Factory::weWork($config);

        // 微信支付


        return $config;
    }

    /**
     * 领取现金红包
     */
    public function  index(){
        $payment = Factory::payment($this->redConfig());
        $redpack = $payment->redpack;


        $redpackData = [
            'nonce_str'=>md5('flanche'.sha1('ideamake'.microtime())),
            'wishing'=>'',
            'mch_billno'   => 'xy123456',
            'send_name'    => '测试红包',
            're_openid'    => 'oxTWIuGaIt6gTKsQRLau2M0yL16E',
            'total_num'    => 1,  //固定为1，可不传
            'total_amount' => 100,  //单位为分，不小于100
            'wishing'      => '祝福语',
            'client_ip'    => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP
            'act_name'     => '测试活动',
            'remark'       => '测试备注',
            // ...
        ];

        $result = $redpack->sendNormal($redpackData);
    }

    public function enterprisePayment(){
        $app = Factory::weWork($this->redConfig());
        $app->transfer->toBalance([
            'partner_trade_no' => md5('flanche'.sha1('ideamake'.microtime())), // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
            'openid' => 'oxTWIuGaIt6gTKsQRLau2M0yL16E',
            'check_name' => 'FORCE_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
            're_user_name' => '王小帅', // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
            'amount' => 10000, // 企业付款金额，单位为分
            'desc' => '理赔', // 企业付款操作说明信息。必填
        ]);
    }

    public function test(){
        try {
            $wechat = new Pay($this->redConfig());
            $options = [
                'partner_trade_no' => time(),
                'openid'           => 'o38gps3vNdCqaggFfrBRCRikwlWY',
                'check_name'       => 'NO_CHECK',
                'amount'           => '100',
                'desc'             => '企业付款操作说明信息',
                'spbill_create_ip' => '127.0.0.1',
            ];
            $result = $wechat->createTransfers($options);
            echo '<pre>';
            var_export($result);
            $result = $wechat->queryTransfers($options['partner_trade_no']);
            var_export($result);

        } catch (Exception $e) {

            // 出错啦，处理下吧
            echo $e->getMessage() . PHP_EOL;

        }
    }

    /**
     * 企业付款
     * @param string $openid 红包接收者OPENID
     * @param int $amount 红包总金额
     * @param string $billno 商户订单号
     * @param string $desc 备注信息
     * @return bool|array
     * @link https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=14_2
     */
    public function transfers()
    {
        # 配置参数
        include_once "App\Utility\Loader.php";
        $openid = $this->request()->getRequestParam('openid');
        $amount = 100;//$this->request()->getRequestParam('amount');
        $bill_no = md5('flanche'.sha1('ideamake'.microtime()));
        $desc   = '测试红包';
        $transfers= new WechatPay($this->redConfig());
       $abc= $transfers->transfers($openid,$amount,$bill_no,$desc);
       var_dump($abc);
    }

        /**
     * 订单查询
     */
    public function redPackFind(){
        $payment = Factory::payment($this->redConfig());
        $redpack = $payment->redpack;
        $mchBillNo = "商户系统内部的订单号（mch_billno）";
        $redpack->info($mchBillNo);
    }

    /**
     * 下载对账单
     */
    public function account(){
        $payment = Factory::payment($this->redConfig());
        $app = $payment->redpack;
        $bill = $app->bill->get('20140603'); // type: ALL
// or
        $bill = $app->bill->get('20140603', 'SUCCESS'); // type: SUCCESS

// 调用正确，`$bill` 为 csv 格式的内容，保存为文件：
        $bill->saveAs('your/path/to', 'file-20140603.csv');
    }


    public function notify(){
        $payment = Factory::payment($this->redConfig());
        $app = $payment->redpack;

        $response = $app->handlePaidNotify(function($message, $fail){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = 查询订单($message['out_trade_no']);

            if (!$order || $order->paid_at) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $order->paid_at = time(); // 更新支付时间为当前时间
                    $order->status = 'paid';

                    // 用户支付失败
                } elseif (array_get($message, 'result_code') === 'FAIL') {
                    $order->status = 'paid_fail';
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        $response->send(); // return $response;
    }

    function push(){
        $payment = Factory::payment($this->redConfig());
        $app = $payment->redpack;
        $app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }

            // ...
        });
    }

    /**
     * 判断用户是否领取过红包
     */
    public function userRedStatus(){
        //更具im_visitor_wechat.vist_id 获取用户openid unionid 在红包领取表判断用户是否领取过红包


    }


}