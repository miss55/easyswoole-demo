<?php

use App\Helper\Arr;
use EasySwoole\Component\Di;


function console()
{
    \App\Helper\Helper::console(...func_get_args());
}

/**
 * Return the default value of the given value.
 *
 * @param mixed $value
 *
 * @return mixed
 */
function value($value)
{
    return $value instanceof Closure ? $value() : $value;
}

/**
 * Get an item from an array or object using "dot" notation.
 *
 * @param mixed                 $target
 * @param string|array|int|null $key
 * @param mixed                 $default
 *
 * @return mixed
 */
function data_get($target, $key, $default = null)
{
    if (is_null($key)) {
        return $target;
    }

    $key = is_array($key) ? $key : explode('.', $key);

    foreach ($key as $i => $segment) {
        unset($key[$i]);

        if (is_null($segment)) {
            return $target;
        }

        if ($segment === '*') {
            if (! is_array($target)) {
                return value($default);
            }

            $result = [];

            foreach ($target as $item) {
                $result[] = data_get($item, $key);
            }

            return $result;
        }

        if (Arr::accessible($target) && Arr::exists($target, $segment)) {
            $target = $target[$segment];
        } elseif (is_object($target) && isset($target->{$segment})) {
            $target = $target->{$segment};
        } else {
            return value($default);
        }
    }

    return $target;
}

function json_encode_chinese($value)
{
    return json_encode($value, JSON_UNESCAPED_UNICODE);
}

function get_basename($class)
{
    $arr = explode("\\", $class);
    if (! empty($arr)) {
        return end($arr);
    }

    return '';
}

/**
 * 获取redis对象
 *
 * @param string $name
 *
 * @return \EasySwoole\Redis\Redis
 * @throws Throwable
 */
function get_redis($name)
{
    $pool = \EasySwoole\Pool\Manager::getInstance()->get($name);
    // console($pool->status());
    return $pool->defer();
}

/**
 * 设置redis连接池
 *
 * @param \App\Pool\RedisPool $pool
 * @param string              $name
 */
function set_redis(\App\Pool\RedisPool $pool, $name)
{
    \EasySwoole\Pool\Manager::getInstance()->register($pool, $name);
}

/**
 * 销毁redis对象
 *
 * @param string                  $name
 * @param \EasySwoole\Redis\Redis $obj
 *
 * @throws Throwable
 */
function unset_redis($name, $obj)
{
    \EasySwoole\Pool\Manager::getInstance()->get($name)->recycleObj($obj);
}

/**
 * @param \EasySwoole\ElasticSearch\ElasticSearch $search
 * @param                                         $config
 */
function set_elastic_search(\EasySwoole\ElasticSearch\ElasticSearch $search)
{
    Di::getInstance()->set('elastic_search', $search);
}

/**
 * @return \EasySwoole\ElasticSearch\Client
 * @throws Throwable
 */
function get_elastic_search()
{
    return Di::getInstance()->get('elastic_search')->client();
}

/**
 * 返回当前日期
 * @return string
 */
function now()
{
    return date('Y-m-d H:i:s');
}
