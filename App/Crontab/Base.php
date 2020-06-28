<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/25
 * Time: 23:58
 */

namespace App\Crontab;


use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

abstract class Base extends AbstractCronTask implements CrontabInterface
{
    const YEARLY    = "@yearly";    # 每年一次 等同于(0 0 1 1 *)
    const ANNUALLY  = "@annually";  # 每年一次 等同于(0 0 1 1 *)
    const MONTHLY   = "@monthly";   # 每月一次 等同于(0 0 1 * *)
    const WEEKLY    = "@weekly";    # 每周一次 等同于(0 0 * * 0)
    const DAILY     = "@daily";     # 每日一次 等同于(0 0 * * *)
    const HOURLY    = "@hourly";    # 每小时一次 等同于(0 * * * *)

    public function run(int $taskId, int $workerIndex)
    {
        $this->before($taskId, $workerIndex);
        $this->job($taskId, $workerIndex);
        $this->after($taskId, $workerIndex);
    }

    private function before(int $taskId, int $workerIndex)
    {
    }

    private function after(int $taskId, int $workerIndex)
    {
    }
}
