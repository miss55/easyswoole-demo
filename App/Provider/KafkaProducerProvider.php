<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/26
 * Time: 15:20
 */

namespace App\Provider;


use App\Pool\KafkaProducerPool;
use EasySwoole\EasySwoole\Config;

class KafkaProducerProvider
{
    const NAME = 'kafka_producer';

    public static function register()
    {
        $poolConfig = new \EasySwoole\Pool\Config();
        $poolConfig->setIntervalCheckTime(60);
        $poolConfig->setMaxObjectNum(120);
        $config = Config::getInstance()->getConf("KAFKA");

        \EasySwoole\Pool\Manager::getInstance()->register(new KafkaProducerPool($poolConfig, $config), self::NAME);
    }
}
