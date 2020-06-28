<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 22:57
 */

namespace App\Task;


interface TaskInterface
{
    static function description(): string;
    function job(int $taskId, int $workerIndex);
}
