<?php
/**
 * Created by PhpStorm.
 * User: Gong
 * Date: 2018/9/22
 * Time: 8:17
 */

namespace App\Process;
use EasySwoole\Core\Swoole\Process\AbstractProcess;
use Swoole\Process;
class Test extends AbstractProcess
{
    public function run(Process $process)
    {
        // TODO: Implement run() method.
        $this->addTick(30000,function (){
            var_dump('this is '.$this->getProcessName().' process tick');
        });
    }
    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }
    public function onReceive(string $str, ...$args)
    {
        // TODO: Implement onReceive() method.
        var_dump('process rec'.$str);
    }
}