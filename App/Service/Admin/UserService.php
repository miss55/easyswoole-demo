<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/27
 * Time: 12:33
 */

namespace App\Service\Admin;


use App\Exception\ShowException;
use App\Exception\TokenException;
use App\Model\AdminModel;
use EasySwoole\Jwt\Jwt;
use EasySwoole\Utility\Hash;

class UserService
{
    const SECRET_KEY = 'jenson.wen';
    const EXPIRE = 86400;

    public function login($name, $password)
    {
        $user = AdminModel::fastInvoke(function () {
            return AdminModel::create()->where(compact('name'))->where('status', AdminModel::STATUS_ACTIVE)->get();
        });
        if (empty($user)) {
            throw new ShowException("登录失败");
        }
        if (! Hash::validatePasswordHash($password, $user['password'])) {
            throw new ShowException("账号不存在");
        }
        # 处理登录信息
        $token = $this->generateToken($user);

        return compact('token');
    }

    public function register($data)
    {
        $this->checkExist($data['name'], $data['email']);
        $date = date('Y-m-d H:i:s');

        $data['status'] = AdminModel::STATUS_ACTIVE;
        $data['created_date'] = $date;
        $data['updated_date'] = $date;
        $data['last_login_date'] = $date;
        $data['password'] = Hash::makePasswordHash($date['password']);

        $res = AdminModel::fastInvoke(function () use ($data) {
            return AdminModel::create($data)->save();
        });

        if ($res === false) {
            throw new ShowException("注册失败");
        }
    }

    public function decodeToken($token)
    {
        $jwtObject = Jwt::getInstance()->setSecretKey(self::SECRET_KEY)->decode($token);

        $status = $jwtObject->getStatus();

        switch ($status) {
            case  1:
                return $jwtObject;
            case  -1:
                throw new TokenException("token无效");
            case  -2:
                throw new TokenException("token过期");
        }
        throw new TokenException("请登录！");
    }

    protected function checkExist($name, $email)
    {
        $user = AdminModel::fastInvoke(function () use ($name, $email) {
            return AdminModel::create()
                             ->where(compact('name'))
                             ->where('email', $email, '=', 'or')
                             ->get();
        });
        if (! empty($user)) {
            throw new ShowException("账号已存在，请登录");
        }
    }

    private function generateToken(AdminModel $user)
    {
        $jwtObject = Jwt::getInstance()->setSecretKey(self::SECRET_KEY) // 秘钥
                        ->publish();
        $time = time();
        $jwtObject->setAlg('HMACSHA256'); // 加密方式
        $jwtObject->setAud($user->name); // 用户
        $jwtObject->setExp($time + self::EXPIRE); // 过期时间
        $jwtObject->setIat($time); // 发布时间
        $jwtObject->setIss(self::SECRET_KEY); // 发行人
        $jwtObject->setJti(md5($time)); // jwt id 用于标识该jwt
        $jwtObject->setNbf($time + 60 * 5); // 在此之前不可用
        // $jwtObject->setSub('主题'); // 主题

        // 自定义数据
        $jwtObject->setData([
            'email' => $user->email,
        ]);

        // 最终生成的token
        return $jwtObject->__toString();
    }
}
