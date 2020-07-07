<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/26
 * Time: 22:50
 */

namespace App\Task;


class TestTask extends Base
{

    static function description(): string
    {
        return "this is test task";
    }

    function job(int $taskId, int $workerIndex)
    {
        console("task deal  taskId: {$taskId}, workerIndex:{$workerIndex}");
    }
}
