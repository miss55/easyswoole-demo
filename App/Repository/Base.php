<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/6/26
 * Time: 23:34
 */

namespace App\Repository;


class Base
{
    const DEFAULT_EXPIRES = [3360, 10080];
    /**
     * @var string
     */
    protected $prefix;

    public function __construct()
    {
        $this->prefix = 'jenson_';
    }

    /**
     * @return \EasySwoole\Redis\Redis
     * @throws \Throwable
     */
    protected function getCache()
    {
        return get_redis('redis');
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected function getExpire()
    {
        return random_int(self::DEFAULT_EXPIRES[0], self::DEFAULT_EXPIRES[1]);
    }
}
