<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/27
 * Time: 10:56
 */

namespace App\Model;


use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\DbManager;

class Base extends AbstractModel
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public static function fastInvoke(\Closure $func)
    {
        return DbManager::getInstance()->invoke($func);
    }
}
