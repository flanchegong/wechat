<?php
/**
 * Created by PhpStorm.
 * User: flanche
 * Date: 2018/9/21
 * Time: 10:40
 */

namespace App\Model;

Use EasySwoole\Core\Component\logger;
use EasySwoole\Core\Component\Di;
use Illuminate\Database\Capsule\Manager as Capsule;
use  Illuminate\Database\Eloquent\Model  as Eloquent;
class VisitorWechat extends Eloquent
{
    protected  $table = 'visitor_wechat';

    //检查openid 是否已经创建，如果创建直接返回ui都给用户
    private function getUserByOpenID($openid){
        //logger::getInstance()->console('getUserByOpenID : ' . $openid);
        //$res = $this->link()->where ('openId', $openid)->get($this->table, null, 'uid');
        $res=Capsule::table($this->table)->where('openId','=', $openid)->get();
        return !empty($res) ?: $res['uid'];
    }

    //检查openid 是否已经创建，如果创建直接返回ui都给用户
    public function getOpenidByToken($token){
       // logger::getInstance()->console('getOpenidByToken : ' . $token);
        $res=Capsule::table($this->table)->where('login_token','=', $token)->get();
        return !empty($res) ?: $res['openid'];
    }

    //检查openid 是否已经创建，如果创建直接返回ui都给用户
    public function getOpenidByVisitId($visitId){
       // logger::getInstance()->console('getOpenidByToken : ' . $visitId);
        $res=Capsule::table($this->table)->where('visit_id','=', $visitId)->get();
        return !empty($res) ?: $res['openid'];
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