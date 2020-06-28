<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/25
 * Time: 23:30
 */

namespace App\Provider;


use EasySwoole\EasySwoole\Core;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\HotReload\HotReload;
use EasySwoole\HotReload\HotReloadOptions;

/**
 * 提供热加载生产环境不加载
 * Class HotReloadProvider
 *
 * @package App\Core
 */
class HotReloadProvider
{
    public static function register()
    {
        if (! Core::getInstance()->isDev()) {
            return;
        }
        // 配置同上别忘了添加要检视的目录
        $hotReloadOptions = new HotReloadOptions;
        $hotReload = new HotReload($hotReloadOptions);
        $hotReloadOptions->setMonitorFolder([EASYSWOOLE_ROOT . '/App']);

        $server = ServerManager::getInstance()->getSwooleServer();
        $hotReload->attachToServer($server);
    }
}
