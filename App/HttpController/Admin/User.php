<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/27
 * Time: 11:04
 */

namespace App\HttpController\Admin;


use App\Exception\ShowException;
use App\Service\Admin\UserService;

class User extends Base
{
    /**
     * @var UserService
     */
    public $service;

    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $token = $this->getService()->login($this->validateData['name'], $this->validateData['password']);
        $this->writeSuccess("登录成功", $token);
    }

    public function register()
    {
        $data = $this->validateData;
        $this->getService()->register($data);
        $this->writeSuccess("注册成功");
    }

    protected function getService() {
        if (empty($this->service)) {
            $this->service = new UserService();
        }
        return $this->service;
    }

}
