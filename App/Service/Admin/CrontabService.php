<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/27
 * Time: 9:50
 */

namespace App\Service\Admin;


use App\Exception\ShowException;
use EasySwoole\EasySwoole\Bridge\Bridge;
use EasySwoole\EasySwoole\Bridge\BridgeCommand;
use EasySwoole\EasySwoole\Bridge\Package;

class CrontabService
{
    /**
     * @var Package
     */
    protected $package;

    /**
     * @return array
     * @throws ShowException
     * @throws \EasySwoole\EasySwoole\Bridge\Exception
     */
    public function list()
    {
        $this->initPackage(BridgeCommand::CRON_INFO);
        $this->send();
        $this->dealError("crontab info is abnormal");

        return $this->generateShowData();

    }

    /**
     * @throws ShowException
     * @throws \EasySwoole\EasySwoole\Bridge\Exception
     */
    public function exec($name)
    {
        $this->initPackage(BridgeCommand::CRON_RUN);
        $this->package->setArgs($name);
        $this->send();
        $this->dealError("run error");
    }

    /**
     * @throws ShowException
     * @throws \EasySwoole\EasySwoole\Bridge\Exception
     */
    public function stop($name)
    {
        $this->initPackage(BridgeCommand::CRON_STOP);
        $this->package->setArgs($name);
        $this->send();
        $this->dealError("stop error");
    }

    /**
     * @throws ShowException
     * @throws \EasySwoole\EasySwoole\Bridge\Exception
     */
    public function resume($name)
    {
        $this->initPackage(BridgeCommand::CRON_RESUME);
        $this->package->setArgs($name);
        $this->send();
        $this->dealError("resume error");
    }

    /**
     * @param $command
     *
     */
    protected function initPackage($command)
    {
        $package = new Package();
        $package->setCommand($command);

        $this->package = $package;
    }

    /**
     * @param Package $package
     *
     * @throws \EasySwoole\EasySwoole\Bridge\Exception
     */
    protected function send()
    {
        $this->package = Bridge::getInstance()->send($this->package);
    }

    /**
     * @param $msg
     *
     * @throws ShowException
     */
    protected function dealError($msg)
    {
        if ($this->package->getStatus() !== Package::STATUS_SUCCESS) {
            throw new ShowException($this->package->getArgs());
        }
        if (empty($this->package->getArgs())) {
            throw new ShowException($msg);
        }
    }

    /**
     * @return array
     */
    public function generateShowData(): array
    {
        $data = $this->package->getArgs();

        $resultData = [];
        foreach ($data as $k => $v) {
            $resultData[] = [
                'name' => $k,
                'rule' => $v['taskRule'],
                'next_run_time' => date('Y-m-d H:i:s', $v['taskNextRunTime']),
                'run_times' => $v['taskRunTimes'],
                'is_stop' => $v['isStop'],
            ];
        }

        return $resultData;
    }
}
