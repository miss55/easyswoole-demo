<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/7/4
 * Time: 22:36
 */

namespace App\Command;


class BootstrapCommandProvider
{
    private static $list;

    public static function register()
    {
        self::setList();
        foreach (self::$list as $class) {
            self::add(new $class);
        }
    }

    public static function setList()
    {
        $list = [];
        $list[] = RedisSubscribeCommand::class;
        $list[] = KafkaQueueCustomerCommand::class;
        $list[] = MysqlCommand::class;
        $list[] = RabbitMqCustomerCommand::class;
        self::$list = $list;
    }

    /**
     * 添加命令实例
     *
     * @param $instance
     */
    public static function add($instance)
    {
        \EasySwoole\EasySwoole\Command\CommandContainer::getInstance()->set($instance);
    }
}
