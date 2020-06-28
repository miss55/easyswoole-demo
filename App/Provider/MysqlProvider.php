<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/27
 * Time: 10:48
 */

namespace App\Provider;


use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\ORM\Db\Config;
use EasySwoole\EasySwoole\Config as GlobalConfig;
use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Db\Connection;

class MysqlProvider
{
    public static function initialize()
    {
        $config = new Config(GlobalConfig::getInstance()->getConf("MYSQL"));
        DbManager::getInstance()->addConnection(new Connection($config));
    }

    public static function bindEvent(EventRegister $register)
    {
        $register->add($register::onWorkerStart, function () {
            //链接预热
            DbManager::getInstance()->getConnection()->getClientPool()->keepMin();
        });
    }
}
