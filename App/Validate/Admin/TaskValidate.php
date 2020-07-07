<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/26
 * Time: 23:16
 */

namespace App\Validate\Admin;


use App\Validate\BaseValidate;

class TaskValidate extends BaseValidate
{

    protected function run()
    {
        switch ($this->action) {
            case 'list':
                break;
            case 'deal':
                $this->addColumn('name')->required();
                $this->addColumn('args')->optional();
                break;
            case 'status':
                $this->addColumn('id')->required();
                break;
            case 'show':
                break;
        }
    }
}
