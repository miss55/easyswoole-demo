<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/27
 * Time: 11:03
 */

namespace App\HttpController\Admin;


use App\Exception\TokenException;
use App\Service\Admin\UserService;

class Auth extends Base
{
    public $jwt;

    public function checkAuth()
    {
        $header = $this->request()->getHeader('authorization');
        if (empty($header)) {
            throw new TokenException("请登录");
        }
        $this->jwt = (new UserService())->decodeToken(str_replace('Bearer ', '', $header[0]));
    }

    public function onRequest(?string $action): ?bool
    {
        if (parent::onRequest($action)) {
            $this->checkAuth();
            return true;
        }

        return false;
    }

    public function afterAction(?string $actionName): void
    {
        parent::afterAction($actionName);
        unset($this->jwt);
    }
}
