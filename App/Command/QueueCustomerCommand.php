<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: jenson.wen@winway666.com
 * Date: 2020/7/4
 * Time: 20:39
 */

namespace App\Command;


use App\Third\Kafka\CustomerManage;

/**
 * 使用easyswoole扩展easyswoole/kafka 做队列处理
 * 分别三个小命令
 * start 启动进程池处理消息队列
 * stop 平滑停止消息队列
 * status 查看进程
 * Class QueueCustomerCommand
 *
 * @package App\Command
 */
class QueueCustomerCommand extends BaseCommand
{

    public function commandName(): string
    {
        return "queue_customer";
    }

    public function exec(array $args): ?string
    {
        $manage = new CustomerManage();
        $output = [];
        if (in_array('start', $args)) {
            $manage->start();
        } else if (in_array('stop', $args)) {
            $output = $manage->stop();
        } else if (in_array('status', $args)) {
            $output = $manage->status();
        } else {
            $output = [$this->help($args)];
        }

        return implode("\n", $output). "\n";
    }

    public function help(array $args): ?string
    {
        return self::commandName(). " need start, stop, status";
    }


}
