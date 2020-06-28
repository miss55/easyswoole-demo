<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/29
 * Time: 1:56
 */

namespace App\Crontab;


interface CrontabInterface
{
    public function job(int $taskId,int $workerIndex);

}
