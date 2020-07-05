<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/7/4
 * Time: 11:27
 */

namespace App\Validate\Admin;


use App\Validate\BaseValidate;

class PostValidate extends BaseValidate
{

    protected function run()
    {
        switch ($this->action) {
            case 'add':
                $this->addColumn('title')->required();
                $this->addColumn('content')->required();
                $this->addColumn('status')->required()->between(0, 1);
                break;
            case 'edit':
                $this->addColumn('id')->required()->integer();
                $this->addColumn('title')->required();
                $this->addColumn('content')->required();
                break;
            case 'list':
                $this->addColumn('title')->optional();
                break;
        }
    }
}
