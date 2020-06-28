<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 21:03
 */

namespace App\Validate\Admin;


use App\Validate\BaseValidate;

class RedisValidate extends BaseValidate
{
    public $cursor;

    protected function run()
    {
        switch ($this->action) {
            case  'list':
                $this->addColumn('pattern')->optional();
                break;
            case 'select':
                $this->addColumn('pattern')->optional();
            case 'string':
            case 'array':
                $this->addColumn('name')->required();
                break;
        }
    }
}
