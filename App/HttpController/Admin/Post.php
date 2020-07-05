<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/7/4
 * Time: 10:28
 */

namespace App\HttpController\Admin;


use App\Service\Admin\PostService;

class Post extends Auth
{
    public function list()
    {
        $data = $this->getService()->posts($this->validateData, $this->getPage());
        return $this->writeSuccess("查询成功", $data);
    }

    public function add()
    {
        $this->getService()->add($this->validateData);
        return $this->writeSuccess("添加成功");
    }

    public function edit()
    {
        $this->getService()->update($this->validateData, $this->validateData['id']);
        return $this->writeSuccess("更新成功");
    }
    protected function getService()
    {
        if (empty($this->service)) {
            $this->service = new PostService();
        }

        return $this->service;
    }
}
