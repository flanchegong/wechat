<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/3/3
 * Time: 下午6:41
 */

namespace App\Utility;


use EasySwoole\Config;
use EasySwoole\Core\Component\Di;
class Db
{
    private $db;
    function __construct()
    {
        $conf = Config::getInstance()->getConf('MYSQL');
        $this->db = new \MysqliDb($conf['HOST'],$conf['USER'],$conf['PASSWORD'],$conf['DB_NAME']);
        if(!$this->db = Di::getInstance()->get('MYSQL')){
            $config = Config::getInstance()->getConf('MYSQL');
            $this->db = Di::getInstance()->set('MYSQL',\MysqliDb::class, $config);
            //var_dump($config['trace']);
            //$this->db->setTrace($config['trace']);

            //如果要添加主从配置可以使用下面方法继续添加配置
            //$this->db->addConnection('slave', $c);
        }
    }

    function dbConnector()
    {
        return $this->db;
    }

    //返回实例化的对象
    function link()
    {
        return $this->db;
    }
}