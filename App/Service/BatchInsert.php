<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/7/13
 * Time: 20:46
 */

namespace App\Service;


class BatchInsert
{
    const SPLIT_NUM = 100;
    /**
     * @var array
     */
    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function add($row)
    {
        $this->data[] = $row;
        if (count($this->data) >= self::SPLIT_NUM) {
            $this->batchSave();
        }
    }

    /**
     * 批量保存
     */
    private function batchSave()
    {
        $data = $this->data;
        $this->data = [];
        if (empty($data)) {
            return;
        }
        # batch save
    }

    public function __destruct()
    {
        $this->batchSave();
    }

    public function test()
    {

    }

}

