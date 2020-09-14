<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/3
 * Time: 11:29
 */

namespace App\Model;


use EasySwoole\Component\Singleton;

class ComStatic
{
    use Singleton;

    private static $some;

    public function get()
    {
        if (empty(self::$some)) {
            self::$some = 0;
        }
        self::$some=self::$some+1;
        return self::$some;
    }
}
