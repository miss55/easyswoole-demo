<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/17
 * Time: 14:42
 */

namespace App\Model;


class BaseTest extends Base
{
    const CONNECTION_NAME = 'test';
    protected $connectionName = self::CONNECTION_NAME;
}
