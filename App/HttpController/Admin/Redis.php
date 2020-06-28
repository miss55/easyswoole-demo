<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 19:46
 */

namespace App\HttpController\Admin;


use App\Exception\CursorEmptyException;
use App\Exception\PageEmptyException;
use App\Helper\CursorPage;
use App\Service\Admin\RedisService;

class Redis extends Auth
{
    /**
     * @var RedisService
     */
    public $service;

    /**
     * 列表
     *
     * @throws CursorEmptyException
     * @throws \Throwable
     */
    public function list()
    {
        $page = $this->getCursorPage();
        $pattern = $this->validateData['pattern'];
        $this->writeSuccess($this->getService()->list($pattern, $page));
    }

    /**
     * @throws \Throwable
     */
    public function string()
    {
        $name = $this->validateData['name'];
        $this->writeSuccess($this->getService()->string($name));
    }

    /**
     * @throws PageEmptyException
     * @throws \Throwable
     */
    public function array()
    {
        $page = $this->getPage();
        $name = $this->validateData['name'];
        $this->writeSuccess($this->getService()->array($name, $page));
    }

    /**
     * @throws CursorEmptyException
     * @throws \Throwable
     */
    public function select()
    {
        $page = $this->getCursorPage();
        $name = $this->validateData['name'];
        $pattern = $this->validateData['pattern'];
        $this->writeSuccess($this->getService()->select($name, $pattern, $page));
    }

    public function test()
    {
        $redis = get_redis('redis');

        $index = 0;
        while ($index < 20) {
            $redis->lPush('test_list', $index);
            $index++;
        }
        $this->writeSuccess($index);
    }

    protected function getCursorPage()
    {
        return new CursorPage($this->params);
    }

    public function onException(\Throwable $throwable): void
    {
        if ($throwable instanceof CursorEmptyException) {
            $this->writeSuccess($throwable->getPage()->generateReturn([]));
        }

        parent::onException($throwable);
    }

    protected function getService()
    {
        if (empty($this->service)) {
            $this->service = new RedisService();
        }
        return $this->service;
    }

}
