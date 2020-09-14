<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/19
 * Time: 20:30
 */

namespace App\Command;


use App\Provider\RedisProvider;

class RedisSubscribeCommand extends BaseCommand
{

    public function commandName(): string
    {
        return 'redis_subscribe';
    }

    public function exec(array $args): ?string
    {
        RedisProvider::register();
        go(function () {
            $redis = get_redis('redis');
            $redis->subscribe(function($info, $channel, $message) {
                console($info, "channel: {$channel}", "message: {$message}" );
            }, "test");
        });
        return '';
    }

    public function help(array $args): ?string
    {
        return "";
    }
}
