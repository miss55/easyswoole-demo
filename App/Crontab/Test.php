<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/25
 * Time: 23:58
 */

namespace App\Crontab;


use EasySwoole\EasySwoole\Logger;

class Test extends Base
{

    public static function getRule(): string
    {
        return self::WEEKLY;
    }

    public static function getTaskName(): string
    {
        return "test";
    }

    function run(int $taskId, int $workerIndex)
    {
        console('test doing...');
    }

    function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        Logger::getInstance()->info("crontab error {$taskId} : {$workerIndex}. " . $throwable->getMessage());
    }
}
