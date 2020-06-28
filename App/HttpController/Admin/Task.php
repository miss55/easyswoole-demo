<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 22:55
 */

namespace App\HttpController\Admin;


use App\Service\Admin\TaskService;
use App\Task\TaskFactory;
use EasySwoole\EasySwoole\Task\TaskManager;

class Task extends Auth
{
    /**
     * @var TaskService
     */
    public $service;


    public function list()
    {
        $this->writeSuccess(TaskFactory::list());
    }

    public function deal()
    {
        $name = $this->validateData['name'];
        $args = $this->validateData['args'];
        $id = $this->getService()->deal($name, $args);
        $this->writeSuccess("success", ['id' => $id]);
    }

    public function status()
    {
        $data = $this->getService()->status($this->validateData['id']);
        $this->writeSuccess('', $data);
    }

    public function show()
    {
        $rows = TaskManager::getInstance()->status();
        $this->writeSuccess('success', $rows);
    }

    protected function getService()
    {
        if (empty($this->service)) {
            $this->service = new TaskService();
        }

        return $this->service;
    }
}
