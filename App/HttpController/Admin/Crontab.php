<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/25
 * Time: 22:57
 */

namespace App\HttpController\Admin;


use App\Exception\ShowException;
use App\Service\Admin\CrontabService;

class Crontab extends Auth
{
    public $service;

    /**
     * @throws ShowException
     * @throws \EasySwoole\EasySwoole\Bridge\Exception
     */
    public function list()
    {
        $rows = $this->getService()->list();
        $this->writeSuccess($rows);
    }

    /**
     * @throws ShowException
     * @throws \EasySwoole\EasySwoole\Bridge\Exception
     */
    public function exec()
    {
        $this->getService()->exec($this->validateData['name']);
        $this->writeSuccess("执行成功");
    }

    /**
     * @throws ShowException
     * @throws \EasySwoole\EasySwoole\Bridge\Exception
     */
    public function stop()
    {
        $this->getService()->stop($this->validateData['name']);
        $this->writeSuccess("停止成功");
    }

    /**
     * @throws ShowException
     * @throws \EasySwoole\EasySwoole\Bridge\Exception
     */
    public function resume()
    {
        $this->getService()->resume($this->validateData['name']);
        $this->writeSuccess("重启成功");
    }

    protected function getService()
    {
        if (empty($this->service)) {
            $this->service = new CrontabService;
        }

        return $this->service;
    }

}
