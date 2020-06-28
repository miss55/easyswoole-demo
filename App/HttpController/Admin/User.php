<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/27
 * Time: 11:04
 */

namespace App\HttpController\Admin;


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
        $this->service = new UserService();
    }

    public function login()
    {
        $token = $this->service->login($this->validateData['name'], $this->validateData['password']);
        $this->writeSuccess("登录成功", $token);
    }

    public function register()
    {
        $data = $this->validateData;
        $this->service->register($data);
        $this->writeSuccess("注册成功");
    }

}
