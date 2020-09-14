<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/27
 * Time: 16:40
 */

namespace App\Third;


use Swoole\Table;

abstract class AbstractConfig
{
    protected $config;
    protected $poolConfig;
    protected $table;
    protected $atomic;

    /**
     * @var string
     */
    protected $rootProcessName;
    /**
     * @var string
     */
    protected $childProcessName;
    /**
     * @var string
     */
    protected $baseProcessName;

    protected $workNum;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->setBaseProcessName();
        $this->rootProcessName = "{$this->baseProcessName}_root";
        $this->childProcessName = "{$this->baseProcessName}_child";

        $this->table = $this->getInitTable($this->workNum);
        $this->atomic = new \Swoole\Atomic();
    }


    private function getInitTable($workerNum)
    {
        $table = new Table(1024);
        foreach (range(0, $workerNum - 1) as $num) {
            $table->column('workId', \Swoole\Table::TYPE_INT, 4);
            $table->column('running', \Swoole\Table::TYPE_INT, 4);
        }
        $table->create();

        return $table;
    }

    /**
     * @return string
     */
    public function getRootProcessName(): string
    {
        return $this->rootProcessName;
    }

    /**
     * @return string
     */
    public function getChildProcessName(): string
    {
        return $this->childProcessName;
    }

    /**
     * @return string
     */
    public function getBaseProcessName()
    {
        return $this->baseProcessName;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return mixed
     */
    public function getPoolConfig()
    {
        return $this->poolConfig;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return mixed
     */
    public function getAtomic()
    {
        return $this->atomic;
    }

    /**
     * @return mixed
     */
    public function getWorkNum()
    {
        return $this->workNum;
    }

    abstract protected function setBaseProcessName();
}
