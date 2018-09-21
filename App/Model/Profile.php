<?php
/**
 * Created by PhpStorm.
 * User: flanche
 * Date: 2018/9/13
 * Time: 15:11
 */


namespace App\Model;
use EasySwoole\Core\Component\Di;
Use EasySwoole\Core\Component\logger;
use Illuminate\Database\Capsule\Manager as Capsule;
//需要在这里引入Db类，它负责创建数据库的连接和执行一些通用的数据库操作
use App\Utility\Db;
use  Illuminate\Database\Eloquent\Model  as Eloquent;
class Profile extends Eloquent
{
    //设置表名称
    protected  $table = 'profile';

    //获取或新增数据，这里我们传了两个参数 openid 和 data， data是微信接口返回的我们解密后的数据
    function getOrInsert($openid, $data){

        if($uid = $this->getUserByOpenID($openid)){
            logger::getInstance()->console('getUserByOpenID : ' . $uid);
            return $uid;
        }


        //格式化需要插入的数据
        $row = $this->buildInsertData($data);
        //获取数据库连接，并执行插入，如果插入成功则返回自增id，没有自增id返回true。
        $insertId = Capsule::table('profile')->insert($row);

        logger::getInstance()->console('getOrInsert : ' . $insertId);
        return $insertId;
    }

    //检查openid 是否已经创建，如果创建直接返回ui都给用户
    private function getUserByOpenID($openid){
        logger::getInstance()->console('getUserByOpenID : ' . $openid);
        //$res = $this->link()->where ('openId', $openid)->get($this->table, null, 'uid');
        $res=Capsule::table('profile')->where('openId','=', $openid)->get();
        return !empty($res) ?: $res['uid'];
    }

    //格式化一下要插入的数据不要让不存在的字段加入数组
    private function buildInsertData($params){
        return [
            'openId'    => $params['openId'],
            'nickName'  => $params['nickName'],
            'gender'    => $params['gender'],
            'language'  => $params['language'],
            'city'      => $params['city'],
            'province'  => $params['province'],
            'country'   => $params['country'],
            'avatarUrl' => $params['avatarUrl'],
        ];
    }
}