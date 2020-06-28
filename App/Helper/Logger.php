<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 10:43
 */

namespace App\Helper;


use EasySwoole\Log\LoggerInterface;
use EasySwoole\Utility\File;

class Logger implements LoggerInterface
{
    private $logDir;

    function __construct(string $logDir = null)
    {
        if (empty($logDir)) {
            $logDir = getcwd() . "/Log";
        }
        $this->logDir = $logDir;
    }

    function log(?string $msg, int $logLevel = self::LOG_LEVEL_INFO, string $category = 'debug')
    {
        $prefix = date('Ymd');
        $date = date('Y-m-d H:i:s');
        $levelStr = $this->levelMap($logLevel);
        $filePath = $this->generateFilePath($category) . "/log_{$prefix}.log";
        $str = "[{$date}][{$levelStr}]:{$msg}\n";
        file_put_contents($filePath, "{$str}", FILE_APPEND | LOCK_EX);

        return $str;
    }

    function console(?string $msg, int $logLevel = self::LOG_LEVEL_INFO, string $category = 'console')
    {
        if ($logLevel != self::LOG_LEVEL_INFO) {
            return;
        }
        $date = date('Y-m-d H:i:s');
        $levelStr = $this->levelMap($logLevel);
        echo "[{$date}][{$levelStr}]:{$msg}\n";
    }

    private function levelMap(int $level)
    {
        switch ($level) {
            case self::LOG_LEVEL_INFO:
                return 'info';
            case self::LOG_LEVEL_NOTICE:
                return 'notice';
            case self::LOG_LEVEL_WARNING:
                return 'warning';
            case self::LOG_LEVEL_ERROR:
                return 'error';
            default:
                return 'unknown';
        }
    }

    private function generateFilePath($category)
    {
        $path = str_replace('.', '/', $category);
        $path = "{$this->logDir}/{$path}";
        File::createDirectory($path);

        return $path;
    }
}
