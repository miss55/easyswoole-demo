<?php
/**
 * Created by PhpStorm.
 * User: jenson
 * EMAIL: 810204005@qq.com
 * Date: 2020/8/29
 * Time: 11:42
 */

namespace App\Command;


use App\Third\RabbitMq\Manager;

class RabbitMqCustomerCommand extends BaseCommand
{

    public function commandName(): string
    {
        return "rabbit_mq";
    }

    public function exec(array $args): ?string
    {
        $manage = new Manager();
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

        return implode("\n", $output) . "\n";
    }

    public function help(array $args): ?string
    {
        return self::commandName() . " need start, stop, status";
    }
}
