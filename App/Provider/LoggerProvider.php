<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 10:55
 */

namespace App\Provider;


use App\Helper\Logger;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\SysConst;

/**
 * 自定义日志加载
 * Class LoggerProvider
 *
 * @package App\Provider
 */
class LoggerProvider
{
    public static function register()
    {
        $dir = Config::getInstance()->getConf("LOG_DIR");
        Di::getInstance()->set(SysConst::LOGGER_HANDLER, new Logger($dir));
    }
}
