<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 23:34
 */

namespace App\Repository;


class TaskRepository extends Base
{
    public function get($name)
    {
        $rs = $this->getCache()->get($this->getName($name));
        if (empty($rs)) {
            return [];
        }

        return json_decode($rs, true);
    }

    public function set($name, $data)
    {
        $this->getCache()->setEx($this->getName($name), $this->getExpire(), json_encode_chinese($data));
    }

    protected function getName($name)
    {
        return $this->prefix . "task_" . $name;
    }

    protected function getExpire()
    {
        return 60;
    }
}
