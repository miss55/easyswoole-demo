<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 10:16
 */

namespace App\Validate\Admin;


use App\Validate\BaseValidate;

class CrontabValidate extends BaseValidate
{

    private function addName()
    {
        $this->addColumn('name')->required();
    }

    protected function run()
    {
        switch ($this->action) {
            case 'exec':
            case 'stop':
            case 'resume':
                $this->addName();
                break;
        }
    }
}
