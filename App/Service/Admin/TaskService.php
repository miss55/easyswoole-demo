<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/27
 * Time: 10:11
 */

namespace App\Service\Admin;


use App\Repository\TaskRepository;
use App\Task\TaskFactory;
use EasySwoole\EasySwoole\Task\TaskManager;

class TaskService
{
    public function deal($name, $args)
    {
        $args = $args ? json_decode($args, true) : [];
        $task = TaskFactory::get($name, $args);

        return TaskManager::getInstance()->async($task);
    }

    public function status($id)
    {
        $rep = new TaskRepository();
        return $rep->get($id);
    }

}
