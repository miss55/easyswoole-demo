<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/26
 * Time: 22:57
 */

namespace App\Task;


interface TaskInterface
{
    static function description(): string;
    function job(int $taskId, int $workerIndex);
}
