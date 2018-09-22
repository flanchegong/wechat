<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/3/3
 * Time: 下午6:41
 */

namespace App\Utility;
use EasySwoole\Config;
class Db
{
    private $db;
    function __construct()
    {
        $conf = Config::getInstance()->getConf('MYSQL');
        $this->db = new \MysqliDb($conf['host'],$conf['username'],$conf['password'],$conf['db']);
    }
    function dbConnector()
    {
        return $this->db;
    }
}