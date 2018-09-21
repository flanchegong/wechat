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
class Redpack extends Eloquent
{
    protected  $table = 'redpack';
    //获取或新增数据，这里我们传了两个参数 openid 和 data， data是微信接口返回的我们解密后的数据
    function getOrInsert($data,$visitors){
        //格式化需要插入的数据
        $row = $this->buildInsertData($data,$visitors);
        //获取数据库连接，并执行插入，如果插入成功则返回自增id，没有自增id返回true。
        $insertId = Capsule::table($this->table)->insert($row);

        //logger::getInstance()->console('getOrInsert : ' . $insertId);
        return $insertId;
    }


    //检查openid 是否已经创建，如果创建直接返回ui都给用户
    private function getUserByOpenID($openid){
        //logger::getInstance()->console('getUserByOpenID : ' . $openid);
        //$res = $this->link()->where ('openId', $openid)->get($this->table, null, 'uid');
        $res=Capsule::table($this->table)->where('openId','=', $openid)->get();
        return !empty($res) ?: $res['uid'];
    }

    //格式化一下要插入的数据不要让不存在的字段加入数组
    private function buildInsertData($paramsone,$paramstwo){
        return [
            'openid'    => $paramstwo['openid'],
            'phone'  => $paramsone['phoneNumber'],
            'unionid'  => $paramstwo['unionid'],
            'addtime'  => $paramsone['watermark']['timestamp'],
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