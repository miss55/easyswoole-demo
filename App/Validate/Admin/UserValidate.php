<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/27
 * Time: 11:05
 */

namespace App\Validate\Admin;


use App\Validate\BaseValidate;
use App\Validate\UserValidate\EmailValidate;

class UserValidate extends BaseValidate
{

    protected function run()
    {
        switch ($this->action) {
            case 'login':
                $this->addColumn('name')->required();
                $this->addColumn('password')->required();
                break;
            case 'register':
                $this->addColumn('name')->required();
                $this->addColumn('email')->required()->callUserRule(new EmailValidate());
                $this->addColumn('password')->required();
                break;
        }
    }
}
