<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/26
 * Time: 15:38
 */

namespace App\Provider;


use App\Model\ComStatic;
use EasySwoole\EasySwoole\Swoole\EventRegister;

class MainServerProvider
{
    public static function initializes()
    {
        MysqlProvider::initialize();
    }

    public static function registers(EventRegister $register)
    {
        HotReloadProvider::register();
        CrontabProvider::register();
        RedisProvider::register();
        MysqlProvider::bindEvent($register);
        ElasticSearchProvider::register();
        KafkaProducerProvider::register();
        RabbitMqProvider::register();
    }
}
