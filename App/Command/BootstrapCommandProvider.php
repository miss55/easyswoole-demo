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
    public static function register()
    {
        self::add(new QueueCustomerCommand());
    }

    /**
     * 添加命令实例
     * @param $instance
     */
    public static function add($instance)
    {
        \EasySwoole\EasySwoole\Command\CommandContainer::getInstance()->set($instance);
    }
}
