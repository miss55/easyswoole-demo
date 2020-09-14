<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/29
 * Time: 11:04
 */

namespace App\Provider;


use App\Pool\KafkaProducerPool;
use App\Pool\RabbitMqProducerPool;
use EasySwoole\EasySwoole\Config;

class RabbitMqProvider
{
    const NAME = 'RABBIT_MQ';

    public static function register()
    {
        $poolConfig = new \EasySwoole\Pool\Config();
        $poolConfig->setIntervalCheckTime(60);
        $poolConfig->setMaxObjectNum(120);
        $config = new \App\Third\RabbitMq\Config();

        \EasySwoole\Pool\Manager::getInstance()->register(new RabbitMqProducerPool($poolConfig, $config), self::NAME);
    }

}
