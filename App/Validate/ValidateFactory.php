<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/26
 * Time: 13:20
 */

namespace App\Validate;


use App\Exception\ShowException;

class ValidateFactory
{
    /**
     * @param $class
     *
     * @return BaseValidate
     * @throws ShowException
     */
    public static function get($class, $action)
    {
        $class = __NAMESPACE__ . $class . "Validate";
        if (class_exists($class)) {
            return new $class($action);
        }
        throw new ShowException("校验类不存在");
    }
}
