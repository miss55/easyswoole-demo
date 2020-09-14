<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/27
 * Time: 16:46
 */

namespace App\Third\Kafka;


use App\Third\AbstractConfig;

class Config extends AbstractConfig
{

    protected function init() {
        $this->config = \EasySwoole\EasySwoole\Config::getInstance()->getConf("KAFKA");
        $this->poolConfig = $this->config['pool'];
        $this->workNum = $this->poolConfig['workerNum'];
        parent::init();
    }

    protected function setBaseProcessName()
    {
        $this->baseProcessName = $this->config["pool"]['queueName'];
    }
}
