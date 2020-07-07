<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/26
 * Time: 22:49
 */

namespace App\Task;


use App\Helper\Helper;
use App\Repository\TaskRepository;
use EasySwoole\Task\AbstractInterface\TaskInterface as DefaultTaskInterface;

abstract class Base implements DefaultTaskInterface, TaskInterface
{
    private $begin;

    function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        $name = strtolower(get_basename(static::class));
        $category = "task.{$name}";
        Helper::error("task捕获到一个异常", null, $throwable, $category);
        $data = [
            'name' => $this->getBaseName(),
            'status' => -1,
            'message' => $throwable->getMessage(),
            'worker_index' => $workerIndex,
            'diff' => time() - $this->begin,
        ];
        $this->getCache()->set($taskId, $data);
    }

    function run(int $taskId, int $workerIndex)
    {
        $this->before($taskId, $workerIndex);
        $this->job($taskId, $workerIndex);
        $this->after($taskId, $workerIndex);
    }

    private function before(int $taskId, int $workerIndex)
    {
        $this->begin = time();
        $data = [
            'name' => $this->getBaseName(),
            'status' => 1,
            'message' => 'deal task begin',
            'worker_index' => $workerIndex,
            'diff' => 0,
        ];
        $this->getCache()->set($taskId, $data);
    }

    private function after(int $taskId, int $workerIndex)
    {
        $data = [
            'name' => $this->getBaseName(),
            'status' => 2,
            'message' => 'deal task success',
            'worker_index' => $workerIndex,
            'diff' => time() - $this->begin,
        ];
        $this->getCache()->set($taskId, $data);
    }

    private function getCache()
    {
        return new TaskRepository();
    }

    private function getBaseName()
    {
        return get_basename(static::class);
    }
}
