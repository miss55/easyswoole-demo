<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/7/6
 * Time: 22:03
 */

namespace App\HttpController\Api;


use App\HttpController\Base;

class Test extends Base
{
    /**
     * 返回json数据 消息正确
     *
     * @return bool
     */
    public function testSuccessJson()
    {
        return $this->writeSuccess("获取成功");
    }

    /**
     * 返回json数据 消息失败
     * @return bool
     */
    public function testErrorJson()
    {
        return $this->writeError("获取失败");
    }

    /**
     * 返回正确的字符串
     * @return bool
     */
    public function testString()
    {
        return $this->response()->write("正确的字符串");
    }

    public function testError()
    {
        $this->response()->withStatus(404);
    }
}
