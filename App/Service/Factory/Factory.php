<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/7/15
 * Time: 0:20
 */

namespace App\Service\Factory;


class Factory
{
    public static function get($name)
    {
        $name = ucfirst($name);
        $class = __NAMESPACE__. '\\'. $name;
        if (class_exists($class)) {
            return new $class;
        }
        throw new \Exception("class {$name} not found");
    }
}
