<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/27
 * Time: 15:53
 */

namespace App\Third;


use Swoole\Runtime;

abstract class AbstractManager
{
    /**
     * @var AbstractConfig
     */
    protected $config;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->setConfig();
    }

    abstract protected function setConfig();


    /**
     * 启动进程池处理队列
     * 目前部分配置写死
     */
    public function start() {
        swoole_set_process_name($this->config->getRootProcessName());
        Runtime::enableCoroutine();
        $this->dealStart();
    }

    abstract protected function dealStart();

    /**
     * 停止进程
     */
    public function stop()
    {
        $name = $this->config->getRootProcessName();
        $cmd = "ps -ef | grep \"{$name}\" | grep -v grep | awk '{print $2}'| xargs kill -15";
        exec($cmd);

        return ["stop success"];
    }

    /**
     * 展示进程状态
     *
     * @return array
     */
    public function status()
    {
        $name = $this->config->getBaseProcessName();
        $cmd = "ps -ef | grep \"{$name}\" | grep -v grep";
        exec($cmd, $output);
        if (empty($output)) {
            $output = ["当前没进程..."];
        } else {
            array_unshift($output, "进程状态:");
        }

        return $output;
    }
}
