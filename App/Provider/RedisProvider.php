<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 13:47
 */

namespace App\Provider;


use EasySwoole\EasySwoole\Config;

class RedisProvider
{
    const REDIS_NAME = 'redis';

    public static function register()
    {
        $data = Config::getInstance()->getConf('REDIS');
        $config = new \EasySwoole\Redis\Config\RedisConfig($data);
        $poolConfig = new \EasySwoole\Pool\Config();
        $poolConfig->setIntervalCheckTime(60);
        set_redis(new \App\Pool\RedisPool($poolConfig, $config), self::REDIS_NAME);
    }

}
