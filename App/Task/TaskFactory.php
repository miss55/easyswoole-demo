<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/6/26
 * Time: 23:00
 */

namespace App\Task;


use App\Exception\UnknownTaskException;
use EasySwoole\Utility\File;

class TaskFactory
{
    public static function list()
    {
        $path = __DIR__ . '/';
        $array = File::scanDirectory($path);

        $rows = [];
        foreach ($array['files'] as $file) {
            $name = str_replace($path, '', $file);
            if (strpos($name, 'Task.php') <= 1) {
                continue;
            }
            $name = str_replace(".php", '', $name);

            $rows[] = [
                'name' => $name,
                'description' => self::getClassName($name)::description(),
            ];
        }

        return $rows;
    }

    /**
     * @param $name
     *
     * @return Base
     * @throws UnknownTaskException
     */
    public static function get($name, $args = [])
    {
        $class = self::getClassName($name);
        if (class_exists($class)) {
            return new $class(...$args);
        }
        throw new UnknownTaskException("没找到 task {$name}");
    }

    public static function getClassName($name)
    {
        return __NAMESPACE__ . '\\' . $name;
    }
}
