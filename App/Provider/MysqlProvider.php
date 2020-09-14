<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/27
 * Time: 10:48
 */

namespace App\Provider;


use App\Model\BaseTest;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\ORM\Db\Config;
use EasySwoole\EasySwoole\Config as GlobalConfig;
use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Db\Connection;

class MysqlProvider
{
    public static function initialize()
    {
        self::setDb('MYSQL', 'default');
        self::setDb('MYSQL2', BaseTest::CONNECTION_NAME);
    }

    public static function bindEvent(EventRegister $register)
    {
        $register->add($register::onWorkerStart, function () {
            //链接预热
            DbManager::getInstance()->getConnection()->getClientPool()->keepMin();
        });
    }

    private static function setDb($configName, $name = 'default')
    {
        $config = new Config(GlobalConfig::getInstance()->getConf($configName));
        DbManager::getInstance()->addConnection(new Connection($config), $name);
    }
}
