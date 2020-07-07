<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/26
 * Time: 0:03
 */

namespace App\Provider;


use App\Crontab\Test;
use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Crontab\Crontab;

class CrontabProvider
{
    public static function register()
    {
        foreach (self::getTasks() as $task) {
            Crontab::getInstance()->addTask($task);
        }
    }

    private static function getTasks()
    {
        $tasks = [
            Test::class,
        ];

        return $tasks;
    }
}
