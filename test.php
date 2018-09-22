<?php
/**
 * Created by PhpStorm.
 * User: Gong
 * Date: 2018/9/22
 * Time: 11:32
 */

require 'vendor/autoload.php';
\EasySwoole\Core\Core::getInstance()->initialize();
$model = new \App\Model\User\User();