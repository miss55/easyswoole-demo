<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/26
 * Time: 10:42
 */

namespace App\Helper;


class Helper
{
    /**
     * 打印到控制台
     * 参数随意
     */
    public static function console()
    {
        $msg = json_encode_chinese(func_get_args()) . self::getDebugMsg(__FUNCTION__);
        \EasySwoole\EasySwoole\Logger::getInstance()->console($msg);
    }

    /**
     * 添加notice日志
     *
     * @param string     $msg      错误message
     * @param null|array $args     错误数组
     * @param string     $category 目录
     */
    public static function notice($msg, $args = null, $category = 'notice')
    {
        $msg .= empty($args) ? '' : "\n\targs:" . json_encode_chinese($args);
        $msg .= self::getDebugMsg(__FUNCTION__);
        \EasySwoole\EasySwoole\Logger::getInstance()->notice($msg, $category);

    }

    /**
     * 添加错误日志
     *
     * @param string          $msg       错误message
     * @param null|array      $args      错误数组
     * @param null|\Throwable $exception 异常类
     * @param string          $category  目录
     */
    public static function error($msg, $args = null, $exception = null, $category = 'error')
    {
        $trace = "";
        $argsString = empty($args) ? '' : "\n\targs:" . (is_array($args) ? json_encode_chinese($args) : $args);
        if (! empty($exception) && $exception instanceof \Throwable) {
            $msg .= " " . $exception->getMessage();
            $trace = self::formatExceptionTrace($exception);
        }
        $msg .= $argsString;
        $msg .= $trace;
        $msg .= self::getDebugMsg(__FUNCTION__);
        \EasySwoole\EasySwoole\Logger::getInstance()->error($msg, $category);
    }

    /**
     * 获取debug栈字符串
     *
     * @param $method
     *
     * @return string
     */
    protected static function getDebugMsg($method)
    {
        $debugs = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 6);
        $debug = self::getDebug($method, $debugs);
        if (! empty($debug)) {
            return "\n  debug: file:{$debug['file']},func:{$debug['function']},line:{$debug['line']}";
        }

        return '';
    }

    /**
     * 获取debug栈
     *
     * @param      $method
     * @param null $debugs
     *
     * @return array
     */
    protected static function getDebug($method, $debugs = null)
    {
        $debugs = $debugs ?? debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 6);
        $needStop = false;
        $methodDebug = [];
        foreach ($debugs as $debug) {
            if ($debug['function'] == $method) {
                $methodDebug = $debug;
            }
            if ($needStop) {
                break;
            }
            if (! empty($methodDebug)) {
                $needStop = true;
            }
        }

        return $methodDebug;
    }

    /**
     * 格式化异常trace
     *
     * @param \Throwable $exception
     * @param int        $deep
     *
     * @return string
     */
    public static function formatExceptionTrace(\Throwable $exception, $deep = 3)
    {
        $trace = $exception->getTrace();
        $msg = "";
        $deep = 3;
        while ($deep > 0 && ($tmp = array_shift($trace))) {
            $msg .= "\n\tfile:{$tmp['file']},function:{$tmp['function']},line:{$tmp['line']}";
            $deep--;
        }

        return $msg ? "\n  exception trace:" . $msg : $msg;
    }
}
