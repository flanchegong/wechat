<?php
/**
 * Created by PhpStorm.
 * User: flanche
 * Date: 2018/9/21
 * Time: 9:45
 */

namespace App\Model;
use EasySwoole\Core\Component\Di;
Use EasySwoole\Core\Component\logger;
use Illuminate\Database\Capsule\Manager as Capsule;
use  Illuminate\Database\Eloquent\Model  as Eloquent;
class Visitors extends Eloquent
{
    protected  $table = 'visitors';
    //获取或新增数据，这里我们传了两个参数 openid 和 data， data是微信接口返回的我们解密后的数据
    function getOrInsert($openid, $data){

        if($uid = $this->getUserByOpenID($openid)){
           // logger::getInstance()->console('getUserByOpenID : ' . $uid);
            return $uid;
        }
        //格式化需要插入的数据
        $row = $this->buildInsertData($data);
        //获取数据库连接，并执行插入，如果插入成功则返回自增id，没有自增id返回true。
        $insertId = Capsule::table($this->table)->insert($row);

        //logger::getInstance()->console('getOrInsert : ' . $insertId);
        return $insertId;
    }


    //检查openid 是否已经创建，如果创建直接返回ui都给用户
    private function getUserByOpenID($openid){
       // logger::getInstance()->console('getUserByOpenID : ' . $openid);
        //$res = $this->link()->where ('openId', $openid)->get($this->table, null, 'uid');
        $res=Capsule::table($this->table)->where('openId','=', $openid)->get();
        return !empty($res) ?: $res['uid'];
    }

    //检查openid 是否已经创建，如果创建直接返回ui都给用户
    public function getOpenidByToken($token){
       // logger::getInstance()->console('getOpenidByToken : ' . $token);
        $res=Capsule::table($this->table)->where('login_token','=', $token)->get()->toArray();
        //var_dump($res);
        $res = array_map('get_object_vars', $res);
        //var_dump($res);
        if(!empty($res)) {
            return $res[0];
        }else{
            return false;
        }
    }

    //检查openid 是否已经创建，如果创建直接返回ui都给用户
    public function getOpenidByVisitId($visitId){
        //logger::getInstance()->console('getOpenidByToken : ' . $visitId);
        $res=Capsule::table($this->table)->where('visit_id','=', $visitId)->get();
        return !empty($res) ?: $res['openid'];
    }

    //检查openid 是否已经创建，如果创建直接返回ui都给用户
    public function getSessionKeyByToken($token){
       // logger::getInstance()->console('getOpenidByToken : ' . $token);
        $res=Capsule::table($this->table)->where('login_token','=', $token)->get()->toArray();
        //var_dump($res);
        $res = array_map('get_object_vars', $res);
        //var_dump($res);
        if(!empty($res)) {
            return $res[0];
        }else{
            return false;
        }
    }

    //格式化一下要插入的数据不要让不存在的字段加入数组
    private function buildInsertData($params){
        return [
            'openid'    => $params['openid'],
            'phoneNumber'  => $params['phoneNumber'],
            'purePhoneNumber'    => $params['purePhoneNumber'],
            'countryCode'  => $params['countryCode'],
            'unionid'      => $params['unionid'],
            'timestamp'  => $params['timestamp'],
        ];
    }

    /**
     * Get the relationships for the entity.
     *
     * @return array
     */
    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }
}