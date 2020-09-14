<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/27
 * Time: 15:52
 */

namespace App\Third\RabbitMq;


use App\Third\AbstractManager;
use Swoole\Process\Pool;

/**
 * rabbitmq 队列
 * Class Manager
 *
 * @package App\Third\RabbitMq
 */
class Manager extends AbstractManager
{

    /**
     * 启动进程池处理队列
     * 目前部分配置写死
     */
    protected function dealStart()
    {
        $confObj = $this->config;
        $config = $confObj->getConfig();
        $poolConfig = $confObj->getPoolConfig();
        $childName = $confObj->getChildProcessName();

        $pool = new Pool($poolConfig['workerNum'], 0, 0, true);

        $table = $confObj->getTable();

        // \Swoole\Process::daemon();
        \Swoole\Process::signal(SIGTERM, function (Pool $pool) {
            $pool->shutdown();
            unset($pool);
        });

        $pool->on("WorkerStart", function (Pool $pool, $workerId) use ($childName, $confObj, $table) {
            $processName = "{$childName}_{$workerId}";
            $pool->getProcess()->name($processName);
            if ($table->exist($workerId) && $table->get('running') == false) {
                sleep(2);
                return null;
            }
            $process = new Process($confObj, $workerId);
            $process->run();
        });

        $pool->on("WorkerStop", function ($pool, $workerId) {
            echo "Worker#{$workerId} is stopped\n";
        });

        $pool->start();
    }


    public function getConfig()
    {
        return $this->config;
    }

    protected function setConfig()
    {
        $this->config = new Config();
    }
}
