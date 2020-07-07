<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/29
 * Time: 1:56
 */

namespace App\Crontab;


interface CrontabInterface
{
    public function job(int $taskId,int $workerIndex);

}
