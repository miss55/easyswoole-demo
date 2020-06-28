<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 15:38
 */

namespace App\Provider;


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
    }
}
