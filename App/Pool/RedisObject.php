<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/7/6
 * Time: 0:50
 */

namespace App\Pool;


use EasySwoole\Redis\Redis;

class RedisObject extends Redis implements \EasySwoole\Pool\ObjectInterface
{

    function gc()
    {
    }

    function objectRestore()
    {
    }

    function beforeUse(): ?bool
    {
        return true;
    }
}
