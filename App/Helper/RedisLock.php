<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/9/14
 * Time: 11:26
 */

namespace App\Helper;


use EasySwoole\Redis\CommandConst;

/**
 * redis 分布式锁
 * Class RedisLock
 *
 * @package App\Helper
 */
class RedisLock
{
    protected $name;
    protected $second;
    /**
     * @var string|null
     */
    private $requireId;

    public function __construct($name, $second, $requireId = null)
    {
        $this->name = $name;
        $this->second = $second;
        $this->requireId = $requireId ?? uniqid();
    }

    public function acquire()
    {
        $redis = get_redis("redis");
        $result = $redis->set($this->name, $this->requireId, ['NX', 'EX' => $this->second]);

        return $result === true;
    }

    public function release()
    {
        $script = "if redis.call('get', '{$this->name}') == '{$this->requireId}' then return redis.call('del', '{$this->name}') else return 0 end";
        $result = $this->eval($script, 0, null);
        return $result === 1;
    }

    public function eval($script, $keyNum, $key, ...$data)
    {
        $redis = get_redis("redis");
        $command = array_merge([CommandConst::EVAL, $script, $keyNum, $key,], $data);
        if (! $redis->sendCommand($command)) {
            return false;
        }
        $recv = $redis->recv();
        if ($recv === null) {
            return false;
        }

        return $recv->getData();
    }

}
